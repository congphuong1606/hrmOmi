<?php

namespace App\Console;

use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Models\Session;
use App\Services\TimeOnCalculating;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('queue:work --queue=sendMail --tries=1')->everyMinute()->withoutOverlapping();
        $schedule->command('queue:work --queue=sendNotification --tries=1')->everyMinute()->withoutOverlapping();

        $schedule->call(function () {
            try {
                DB::beginTransaction();
                TimeOnCalculating::calculatingAccumulatedYear(Carbon::now()->year);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Accumulated Year: ' . $e->getMessage());
                \Log::error('Accumulated Year: ' . $e->getTraceAsString());
            }
        })->dailyAt('01:00');

        // Run once per week on Monday at 1 PM...
        $schedule->call(function () {
            try {
                DB::beginTransaction();
                TimeOnCalculating::calculateTimeOn();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Daily TimeOn: ' . $e->getMessage());
                \Log::error('Daily TimeOn: ' . $e->getTraceAsString());
            }
        })->dailyAt('02:00');

//        MailHelper::sendMailCourse();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
