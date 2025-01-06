<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/debts/{debt}/billet', Debts\BilletController::class)->name('debts.billet');
