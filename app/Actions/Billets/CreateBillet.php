<?php

namespace App\Actions\Billets;

use App\Enums\DebtStatus;
use App\Mail\BilletCreated;
use App\Models\Debt\Debt;
use App\Services\Billets\BilletService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBillet
{
    use AsAction;

    public string $commandSignature = 'billets:create {debt} {--notify}';

    public function __construct(
        protected BilletService $billetService
    ) {}

    public function handle(Debt $debt, bool $notify = true): void
    {
        // Verify debt status
        if (! in_array($debt->status, [DebtStatus::PENDING, DebtStatus::PROCESSING])) {
            throw new Exception("Debt {$debt->debtId} is not pending.");
        }

        // Create billet
        $this->billetService->generateBillet($debt);

        // Update debt status
        $debt->update(['status' => DebtStatus::COMPLETED]);

        // Notify user
        if ($notify && $debt->email) {
            Mail::to($debt->email)->send(new BilletCreated($debt));
        }
    }

    public function asCommand(Command $command): void
    {
        $debt = Debt::findOrFail($command->argument('debt'));
        $notify = $command->option('notify');

        $this->handle($debt, $notify);
    }
}
