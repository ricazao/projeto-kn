<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use League\Csv\Reader;

class CsvColumns implements ValidationRule
{
    public function __construct(
        private array $columns
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $reader = Reader::createFromFileObject($value->openFile());
        $reader->setHeaderOffset(0);
        $fileColumns = $reader->getHeader();
        $missingColumns = [];

        foreach ($this->columns as $column) {
            if (! in_array($column, $fileColumns)) {
                $missingColumns[] = $column;
            }
        }

        if (count($missingColumns) > 0) {
            $fail('The file must contain the following columns: '.implode(', ', $missingColumns));
        }
    }
}
