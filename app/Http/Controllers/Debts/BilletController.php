<?php

namespace App\Http\Controllers\Debts;

use App\Actions\Billets\CreateBillet;
use App\Enums\DebtStatus;
use App\Models\Debt\Debt;

class BilletController
{
    public function __invoke(Debt $debt)
    {
        if ($debt->status === DebtStatus::PROCESSING) {
            return 'Boleto em processamento, aguarde alguns instantes e tente novamente.';
        }

        if ($debt->status === DebtStatus::PENDING) {
            CreateBillet::run($debt, false);
        }

        return <<<HTML
        <pre>
        Boleto {$debt->debtId}\n
        Nome: {$debt->name}\n
        Valor: {$debt->debtAmount}\n
        Vencimento: {$debt->debtDueDate->format('d/m/Y')}
        </pre>
        HTML;
    }
}
