<?php

namespace App\Services\Billets;

use App\Models\Debt\Debt;
use App\Services\Billets\Contracts\BilletGeneratorInterface;

class BilletService
{
    public function __construct(
        protected BilletGeneratorInterface $generator
    ) {}

    public function generateBillet(Debt $debt): void
    {
        $this->generator->generate($debt);
    }
}
