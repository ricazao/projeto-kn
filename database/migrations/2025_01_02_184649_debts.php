<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->uuid('debtId')->primary()->unique();
            $table->foreignId('governmentId');
            $table->string('name');
            $table->string('email');
            $table->float('debtAmount');
            $table->date('debtDueDate');
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
        });
    }
};
