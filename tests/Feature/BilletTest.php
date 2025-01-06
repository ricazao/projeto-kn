<?php

namespace Tests\Feature;

use App\Actions\Billets\CreateBillet;
use App\Enums\DebtStatus;
use App\Mail\BilletCreated;
use App\Models\Debt\Debt;
use App\Services\Billets\BilletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BilletTest extends TestCase
{
    use RefreshDatabase;

    public function test_billet_can_be_created()
    {
        $debt = Debt::factory()->pending()->create();

        $this->mock(BilletService::class, function ($mock) {
            $mock->shouldReceive('generateBillet')
                ->once()
                ->with($this->isInstanceOf(Debt::class));
        });

        CreateBillet::run($debt);

        $this->assertDatabaseHas('debts', [
            'debtId' => $debt->debtId,
            'status' => DebtStatus::COMPLETED,
        ]);
    }

    public function test_user_can_be_notified()
    {
        Mail::fake();

        $debt = Debt::factory()->pending()->create();

        CreateBillet::run($debt, notify: true);

        Mail::assertSent(BilletCreated::class);
    }

    public function test_fail_on_creating_billet_for_non_pending_debt()
    {
        $debt = Debt::factory()->completed()->create();

        $this->expectExceptionMessage("Debt {$debt->debtId} is not pending");

        CreateBillet::run($debt);
    }
}
