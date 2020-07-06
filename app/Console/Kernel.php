<?php

namespace App\Console;

use App\FinishedJob;
use App\Timetable;
use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $now = Carbon::now();
            $time = $now->startOfMinute()->toTimeString();
            $day = $now->dayOfWeekIso-1;
            $timetables = Timetable::where('end_time', '<=', $time)->where('day', $day)->get();
            foreach ($timetables as $timetable)
            {
                if ($timetable->finished_jobs->where('week', $timetable->getWeek())->isEmpty())
                {
                    $timetables->map->setAbsentee();
                    $finished_job = new FinishedJob([
                        'finished_at' => $now,
                        'week' => $timetable->getWeek()
                    ]);
                    $timetable->finished_jobs()->save($finished_job);
                    info('Job Done');
                    return;
                }
                info('Job Already Done');
            }
        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
