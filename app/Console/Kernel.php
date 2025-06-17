<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Services\LeaveBalanceService;
use Illuminate\Support\Facades\Log;

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
        $schedule->command('payment:reminder')->dailyAt('00:00');

        // Cập nhật số ngày nghỉ phép của user trong năm theo từng tháng
        $schedule->call(function () {
            LeaveBalanceService::updateMonthlyLeaveBalance();
        })->monthlyOn(1, '00:00');

        //Chạy thự đơn hàng ngày cho tháng sau
        $schedule->command('mealmenus:generate-next-month')
            ->monthlyOn(15, '00:00')
            //  ->dailyAt('09:05')
            ->withoutOverlapping();

        // Chạy update lại suất ăn    
        $schedule->command('mealmenus:update-count-today')
            ->dailyAt('16:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
