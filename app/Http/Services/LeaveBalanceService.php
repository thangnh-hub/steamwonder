<?php

namespace App\Http\Services;

use App\Consts;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\Log;


class LeaveBalanceService
{
    // Tạo số ngày nghỉ phép trong năm
    public static function addLeaveBalance()
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return;
        }
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $params = [
            'user_id' => $user->id,
            'year' => $currentYear,
        ];
        $leaveBalance = LeaveBalance::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $currentYear,
            ],
            [
                'total_leaves' => $currentMonth,
                'available' => $currentMonth,
                'admin_created_id' => $user->id,
            ]
        );
    }
    public static function updateMonthlyLeaveBalance()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $params = [
            'year' => $currentYear,
        ];
        $leaveBalance = LeaveBalance::getSqlLeaveBalance($params)->get();
        foreach ($leaveBalance as $item) {
            if ((int)$item->total_leaves < $currentMonth) {
                $item->total_leaves = $currentMonth;
                $item->available += 1;
                $item->save();
            }
        }
    }
}
