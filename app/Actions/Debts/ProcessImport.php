<?php

namespace App\Actions\Debts;

use App\Models\Debt\Debt;
use App\Models\Debt\Import;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessImport
{
    use AsAction;

    public function handle(Import $import): void
    {
        $path = Storage::path($import->path);
        $reader = Reader::createFromPath($path, 'r')->setHeaderOffset(0);
        $chunks = $reader->chunkBy(10000);

        DB::transaction(function () use ($chunks) {
            foreach ($chunks as $chunk) {
                $data = iterator_to_array($chunk);
                Debt::insertOrIgnore($data);
            }
        });
    }
}
