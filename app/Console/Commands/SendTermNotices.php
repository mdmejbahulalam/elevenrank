<?php

namespace App\Console\Commands;

use App\Models\Licence;
use App\Services\Notifier;
use Illuminate\Console\Command;

class SendTermNotices extends Command
{
    protected $signature = 'emails:term-notices';

    protected $description = 'Send the 30-day, 7-day and term-ended emails once per licence. Run daily from cron.';

    public function handle(Notifier $notifier): int
    {
        $sent = 0;
        $rules = [
            ['renewal30', 'notified_30_at', fn ($q) => $q->whereBetween('expires', [now()->addDays(7), now()->addDays(30)])],
            ['renewal7', 'notified_7_at', fn ($q) => $q->whereBetween('expires', [now(), now()->addDays(7)])],
            ['expired', 'notified_expired_at', fn ($q) => $q->where('expires', '<', now())->where('expires', '>', now()->subDays(14))],
        ];

        foreach ($rules as [$kind, $column, $scope]) {
            $query = Licence::query()->where('status', 'active')->whereNotNull('expires')->whereNotNull('email')->whereNull($column);
            $scope($query);
            foreach ($query->get() as $licence) {
                $notifier->term($licence, $kind);
                $licence->forceFill([$column => now()])->save();
                $sent++;
            }
        }
        $this->info("Sent {$sent} notice(s).");

        return self::SUCCESS;
    }
}
