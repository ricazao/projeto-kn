<?php

namespace Tests\Feature;

use App\Models\Debt\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebtTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_a_debt_billet()
    {
        $debt = Debt::factory()->create();

        $response = $this->get(route('debts.billet', ['debt' => $debt->debtId]));

        $response->assertStatus(200);
        $response->assertSee("Boleto {$debt->debtId}");
    }

    public function test_user_can_see_processing_message()
    {
        $debt = Debt::factory()->processing()->create();

        $response = $this->get(route('debts.billet', ['debt' => $debt->debtId]));

        $response->assertStatus(200);
        $response->assertSee('Boleto em processamento');
    }

    public function test_fail_on_acessing_unexisting_debt()
    {
        $response = $this->get(route('debts.billet', ['debt' => 1]));

        $response->assertStatus(404);
    }
}
