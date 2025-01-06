<?php

namespace App\Http\Controllers\Api\Debts;

use App\Actions\Debts\ProcessImport;
use App\Http\Requests\DebtImportRequest;
use App\Models\Debt\Import;

class ImportController
{
    public function __invoke(DebtImportRequest $request)
    {
        $file = $request->file('file');
        $path = $file->store('debts');

        $import = Import::create([
            'path' => $path,
        ]);

        ProcessImport::dispatch($import);

        return response()->json([
            'message' => 'Import has been scheduled successfully.',
        ]);
    }
}
