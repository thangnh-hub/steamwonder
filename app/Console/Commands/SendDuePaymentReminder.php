<?php

namespace App\Console\Commands;

use App\Mail\DuePaymentReminder;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDuePaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email nhắc có học viên đến hạn thu tiền';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Lấy danh sách học viên đến hạn thu tiền (130 ngày)
        $students = Student::whereDate('day_official', Carbon::today()->subDays(130))->get();

        if ($students->isEmpty()) {
            $this->info('Không có học viên nào đến hạn thu tiền hôm nay.');
            return;
        }

        $email_notify = 'ptthuong.dwn@gmail.com';
        Mail::to($email_notify)->send(new DuePaymentReminder($students));

        $this->info('Đã gửi email nhắc nhở có ' . count($students) . ' học viên cho email ' . $email_notify);
    }
}
