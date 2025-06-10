<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Http\Controllers\Admin\MealMenuDailyController;
use App\Models\MealAges;
use App\Models\Area;

class GenerateNextMonthMealMenus extends Command
{
    protected $signature = 'mealmenus:generate-next-month';
    protected $description = 'Tự động tạo thực đơn hàng ngày cho tháng kế tiếp vào ngày 15 hàng tháng';

    public function handle()
    {
        $nextMonth = Carbon::now()->addMonthNoOverflow();
        $fromDate = $nextMonth->copy()->startOfMonth()->toDateString();
        $toDate = $nextMonth->copy()->endOfMonth()->toDateString();

        $mealAgeIds = MealAges::getSqlMealAge(['status' => 'active'])->pluck('id')->toArray();
        $areaIds = Area::getSqlArea(['status' => 'active'])->pluck('id')->toArray();

        // Gọi controller để tạo thực đơn
        $controller = new MealMenuDailyController();

        foreach ($mealAgeIds as $mealAgeId) {
            foreach ($areaIds as $areaId) {
                $controller->generateDailyMenus($fromDate, $toDate, $mealAgeId, $areaId);
                $this->info("Đã tạo thực đơn từ $fromDate đến $toDate cho nhóm tuổi $mealAgeId và khu vực $areaId");
            }
        }
        return 0;
    }
}
