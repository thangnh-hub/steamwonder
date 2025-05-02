<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;
use App\Models\Receipt;
use App\Models\ReceiptDetail;

class ReceiptService
{
    /**
     * Tạo phiếu thu mới theo kỳ.
     */
    public function createReceiptForStudent(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            // 1. Xác định chu kỳ thanh toán
            $cycle = $student->payment_cycle;

            // 2. Tính tổng tiền cần thu theo đăng ký dịch vụ
            $details = $this->generateReceiptDetails($student, $data['services']);

            // 3. Tính giảm trừ (theo chính sách, điều kiện)
            $discounts = $this->calculateDiscounts($student, $details, $cycle);

            // 4. Lưu bản ghi Receipt + ReceiptDetail
            return $this->saveReceipt($student, $details, $discounts, $data);
        });
    }

    /**
     * Sinh các dòng chi tiết biên lai (receipt_detail) cho kỳ mới.
     */
    protected function generateReceiptDetails(Student $student, $services)
    {
        // Ví dụ: nhân đơn giá từng dịch vụ với số tháng
        return collect($services)->map(function ($service) {
            return [
                'service_id' => $service['id'],
                'month_count' => $service->cycle->months,
                'unit_price' => $service['price'],
                'total_amount' => $service['price'] * $service->cycle->months,
                'start_month' => now()->format('Y-m'), // hoặc từ kỳ
            ];
        });
    }

    /**
     * Tính toán giảm trừ dựa vào các chính sách hoặc điều kiện (deductions).
     */
    protected function calculateDiscounts(Student $student, $details, $cycle)
    {
        // Có thể đọc từ bảng `deductions` & `student_deductions`
        // Ví dụ:
        // - Nếu học giữa tháng: giảm 50% ăn trưa
        // - Nếu nghỉ học > 10 buổi: hoàn trả

        return []; // trả về danh sách các dòng giảm trừ tương ứng từng dịch vụ
    }

    /**
     * Lưu biên lai và các dòng chi tiết vào DB.
     */
    protected function saveReceipt(Student $student, $cycle, $details, $discounts, $data)
    {
        $receipt = Receipt::create([
            'student_id' => $student->id,
            'payment_cycle_id' => $cycle->id,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'receipt_name' => "Phiếu thu kỳ {$cycle->months} tháng",
            'total_amount' => $details->sum('total_amount'),
            'total_discount' => collect($discounts)->sum('amount'),
            'total_paid' => 0,
            'total_due' => 0,
            'status' => 'pending',
            'json_params' => json_encode([
                'discounts' => $discounts,
                'notes' => $data['notes'] ?? null,
            ]),
        ]);

        // Lưu từng dòng receipt_detail
        foreach ($details as $detail) {
            $receipt->details()->create($detail);
        }

        return $receipt;
    }

    /**
     * Cập nhật phiếu thu sau khi điểm danh thực tế, truy thu hoặc hoàn trả.
     */
    public function reconcileReceipt(Receipt $receipt)
    {
        // 1. Lấy danh sách receipt_detail theo kỳ
        $details = $receipt->details;

        // 2. Với mỗi dòng, tính lại số buổi sử dụng thực tế
        foreach ($details as $detail) {
            $adjustment = $this->calculateAdjustment($receipt->student, $detail);
            $detail->update(['adjustment' => $adjustment]);
        }

        // 3. Cập nhật tổng vào bảng receipt
        $receipt->update([
            'total_adjustment' => $receipt->details->sum('adjustment'),
            'total_final' => $receipt->total_amount - $receipt->total_discount + $receipt->details->sum('adjustment'),
            'total_due' => $receipt->total_final - $receipt->total_paid,
        ]);
    }

    /**
     * Tính truy thu hoặc hoàn trả cho 1 dòng chi tiết.
     */
    protected function calculateAdjustment(Student $student, $detail)
    {
        // Ví dụ: nếu học sinh nghỉ quá nhiều, hoàn lại tiền
        // hoặc nếu đi nhiều hơn kỳ đăng ký => truy thu

        return 0;
    }

    private function isFirstCycleOfSchoolYear(Carbon $periodStart): bool
{
    // Ví dụ năm học bắt đầu từ tháng 9
    $schoolYearStartMonth = 9;

    return $periodStart->month === $schoolYearStartMonth;
}
}
