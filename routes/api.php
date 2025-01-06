<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::post('debts/import', Debts\ImportController::class);
