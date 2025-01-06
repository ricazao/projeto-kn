<?php

namespace App\Http\Requests;

use App\Rules\CsvColumns;
use Illuminate\Foundation\Http\FormRequest;

class DebtImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv', new CsvColumns(['debtId', 'governmentId', 'name', 'email', 'debtAmount', 'debtDueDate'])],
        ];
    }
}
