<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Create daily snapshot at midnight
        $schedule->command('snapshot:create daily-backup --compress')
                 ->daily()
                 ->at('00:00')
                 ->description('Create daily database snapshot');
        
        // Create weekly snapshot every Sunday
        $schedule->command('snapshot:create weekly-backup --compress')
                 ->weekly()
                 ->sundays()
                 ->at('01:00')
                 ->description('Create weekly database snapshot');
        
        // Clean up old snapshots (keep last 30 days)
        $schedule->call(function () {
            $disk = Storage::disk('snapshots');
            $files = collect($disk->files())
                ->filter(function ($file) {
                    return preg_match('/\.sql(\.gz)?$/', $file);
                })
                ->sort()
                ->reverse()
                ->slice(30); // Keep only 30 most recent
            
            foreach ($files as $file) {
                $disk->delete($file);
            }
        })->daily()->description('Clean up old snapshots');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}