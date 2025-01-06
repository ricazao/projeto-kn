<?php

namespace App\Services\Billets\Contracts;

use App\Models\Debt\Debt;

interface BilletGeneratorInterface
{
    public function generate(Debt $debt);
}
