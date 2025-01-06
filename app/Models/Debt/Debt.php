<?php

namespace App\Models\Debt;

use App\Enums\DebtStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory,
        HasUuids;

    protected $table = 'debts';

    protected $primaryKey = 'debtId';

    protected $casts = [
        'debtDueDate' => 'date',
        'status' => DebtStatus::class,
    ];

    public $timestamps = false;
}
