<?php

namespace Tests\Feature;

use App\Actions\Billets\CreateBillet;
use App\Actions\Debts\ProcessImport;
use App\Actions\Debts\ProcessPendingDebts;
use App\Models\Debt\Debt;
use App\Models\Debt\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportDebtsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_debts()
    {
        Queue::fake();
        $this->assertDatabaseEmpty('debts_imports');

        $content = <<<'CSV'
        debtId,governmentId,name,email,debtAmount,debtDueDate
        111,222,Fulano,fulano@teste.com,100,2021-01-01
        CSV;

        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        $response = $this->postJson('/api/debts/import', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('debts_imports', 1);
        ProcessImport::assertPushed();
    }

    public function test_debts_are_stored_on_database_after_import()
    {
        $this->assertDatabaseEmpty('debts');

        $content = <<<'CSV'
        debtId,governmentId,name,email,debtAmount,debtDueDate
        111,222,Fulano,fulano@teste.com,100,2021-01-01
        CSV;

        $import = new Import([
            'path' => UploadedFile::fake()->createWithContent('test.csv', $content)->store('debts'),
        ]);

        ProcessImport::run($import);

        $this->assertDatabaseCount('debts', 1);
        $this->assertDatabaseHas('debts', [
            'debtId' => 111,
            'governmentId' => 222,
            'name' => 'Fulano',
            'email' => 'fulano@teste.com',
            'debtAmount' => 100,
            'debtDueDate' => '2021-01-01',
        ]);
    }

    public function test_debts_are_not_duplicated_after_import()
    {
        $this->assertDatabaseEmpty('debts');

        $content = <<<'CSV'
        debtId,governmentId,name,email,debtAmount,debtDueDate
        111,222,Fulano,fulano@teste.com,100,2021-01-01
        CSV;

        $import = new Import([
            'path' => UploadedFile::fake()->createWithContent('test.csv', $content)->store('debts'),
        ]);

        ProcessImport::run($import);
        ProcessImport::run($import);

        $this->assertDatabaseCount('debts', 1);
    }

    public function test_can_process_pending_debts()
    {
        $this->assertDatabaseEmpty('debts');

        Queue::fake();
        Debt::factory()->count(10)->create();

        ProcessPendingDebts::run(1);

        $this->assertDatabaseCount('debts', 10);
        CreateBillet::assertPushed(1);
    }

    public function test_fail_on_request_without_file()
    {
        $response = $this->postJson('/api/debts/import');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    public function test_fail_on_upload_invalid_file_type()
    {
        $response = $this->postJson('/api/debts/import', [
            'file' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }

    public function test_fail_on_upload_csv_missing_columns()
    {
        $content = <<<'CSV'
        name,email,dueDate,amount
        Fulano,fulano@example.com,2021-01-01,100
        CSV;

        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        $response = $this->postJson('/api/debts/import', [
            'file' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }
}
