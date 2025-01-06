<?php

namespace App\Actions\Debts;

use App\Actions\Billets\CreateBillet;
use App\Enums\DebtStatus;
use App\Models\Debt\Debt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessPendingDebts
{
    use AsAction;

    public string $commandSignature = 'debts:process-pending {--limit=}';

    public string $commandDescription = 'Process pending debts';

    public function handle(int $limit = 2000): void
    {
        $debts = Debt::query()
            ->select('debtId')
            ->where('status', DebtStatus::PENDING)
            ->limit($limit)
            ->get();

        $debts->each(fn ($debt) => CreateBillet::dispatch($debt));

        DB::transaction(function () use ($debts) {
            Debt::query()
                ->whereIn('debtId', $debts->pluck('debtId'))
                ->update(['status' => DebtStatus::PROCESSING]);
        });
    }

    public function asCommand(Command $command): void
    {
        if ($limit = $command->option('limit')) {
            $this->handle((int) $limit);

            return;
        }

        $this->handle();
    }
}
