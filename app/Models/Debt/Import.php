<?php

namespace App\Models\Debt;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasUuids;

    protected $table = 'debts_imports';
}
