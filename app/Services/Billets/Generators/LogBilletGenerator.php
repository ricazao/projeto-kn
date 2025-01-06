<?php

namespace App\Services\Billets\Generators;

use App\Models\Debt\Debt;
use App\Services\Billets\Contracts\BilletGeneratorInterface;
use Illuminate\Support\Facades\Log;

class LogBilletGenerator implements BilletGeneratorInterface
{
    public function generate(Debt $debt)
    {
        Log::channel('billets')->info("Billet created for debt {$debt->debtId}");
    }
}
