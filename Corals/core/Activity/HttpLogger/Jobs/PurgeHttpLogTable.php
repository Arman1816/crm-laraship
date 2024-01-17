<?php


namespace Corals\Activity\HttpLogger\Jobs;


use Carbon\Carbon;
use Corals\Activity\HttpLogger\Models\HttpLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurgeHttpLogTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            logger('Purge Http Logger');

            $maxAgeInDays = config('http_logger.delete_records_older_than_days', 15);

            $cutOffDate = Carbon::now()->subDays($maxAgeInDays)->format('Y-m-d H:i:s');

            $totalDeleted = 0;

            $maxTries = 10000;

            do {
                $amountDeleted = HttpLog::where('created_at', '<', $cutOffDate)
                    ->take(1000)
                    ->delete();

                $totalDeleted += $amountDeleted;

                logger('Partial Purge: ' . $amountDeleted);

                if ($maxTries === 0) {
                    logger('Reached Max Tries');
                    break;
                }

                $maxTries--;
            } while ($amountDeleted > 0);

            logger('Total Purged: ' . $totalDeleted);
            logger('Purge Http Logger Completed');
        } catch (\Exception $exception) {
            report($exception);
        }
    }
}
