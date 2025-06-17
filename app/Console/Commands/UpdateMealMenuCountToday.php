<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\MealAges;
use App\Models\Area;
use App\Http\Controllers\Admin\MealMenuDailyController;

class UpdateMealMenuCountToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mealmenus:update-count-today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật lại số suất ăn của thực đơn hàng ngày dựa trên điểm danh ăn';

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
        $today = Carbon::today()->toDateString();
        // $today = Carbon::tomorrow()->toDateString();

        // Lấy tất cả nhóm tuổi và khu vực active
        $mealAgeIds = MealAges::where('status', 'active')->pluck('id');
        $areaIds = Area::where('status', 'active')->pluck('id');

        $controller = new MealMenuDailyController();

        foreach ($mealAgeIds as $mealAgeId) {
            foreach ($areaIds as $areaId) {
                $result = $controller->updateMealMenuCount($today, $areaId, $mealAgeId);
                if ($result) {
                    $this->info("✔ Đã cập nhật suất ăn cho ngày $today - Khu vực $areaId - Nhóm tuổi $mealAgeId");
                } else {
                    $this->warn("⚠ Không có thực đơn để cập nhật - Ngày $today - Khu vực $areaId - Nhóm tuổi $mealAgeId");
                }
            }
        }

        return 0;
    }
}
