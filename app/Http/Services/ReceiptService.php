<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Student;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReceiptService
{
    /**
     * Tạo phiếu thu mới cho học sinh.
     */
    public function createReceiptForStudent(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            $cycle = $student->paymentCycle;
            $policies = $student->studentPolicies;
            $deductions = $this->getDeductions();
            $startDate = Carbon::parse($data['enrolled_at']);
            $includeCurrent = $data['include_current_month'] ?? true;

            $details = $this->generateReceiptDetails($student, $cycle,$policies, $data['services'], $startDate, $includeCurrent);

            return $this->saveReceipt($student, $cycle,$policies, $details, $data);
        });
    }

    /**
     * Sinh các dòng chi tiết biên lai.
     */
    protected function generateReceiptDetails(Student $student, $cycle, $services, Carbon $startDate, bool $includeCurrent)
    {
        $details = [];
        foreach ($services as $service) {
            // Tìm mức chi phí đang áp dụng vào ngày nhập học
            $matchedDetail = collect($service->serviceDetail)->first(function ($detail) use ($startDate) {
                $start = Carbon::parse($detail['start_at']);
                $end = $detail['end_at'] ? Carbon::parse($detail['end_at']) : null;

                return $startDate->gte($start) && ($end === null || $startDate->lte($end));
            });
            $price = $matchedDetail['price'] ?? 0;
            $quantity = $matchedDetail['quantity'] ?? 0;

            switch ($service->service_type) {
                case Consts::SERVICE_TYPE['monthly']:
                    $monthCount = $cycle->months;
                    $firstMonth = $startDate->copy();
                    // Ở đây cần xử lý trường hợp đặc biệt dành cho tháng hiện tại (nhập học)
                    $discount_amount = $this->calculateDiscount($student, $service, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $firstMonth->format('Y-m'),
                        'by_number' => $quantity,
                        'unit_price' => $price,
                        'amount' => $price * $quantity,
                        'discount_amount' => $discount_amount,
                        'final_amount' => $price * $quantity - $discount_amount,
                    ];

                    // Thêm tháng vừa tính
                    $firstMonth->addMonth();
                    // Nếu không bao gồm tháng hiện tại thì giảm số tháng đi 1
                    if (!$includeCurrent) {
                        $monthCount -= 1;
                    }
                    // Tính toán các tháng còn lại
                    for ($i = 0; $i < $monthCount; $i++) {
                        $month = $firstMonth->copy()->addMonths($i)->format('Y-m');
                        $discount_amount = $this->calculateDiscount($student, $service);
                        $details[] = [
                            'service_id' => $service->id,
                            'month' => $month,
                            'by_number' => $quantity,
                            'unit_price' => $price,
                            'amount' => $price * $quantity,
                            'discount_amount' => $discount_amount,
                            'final_amount' => $price * $quantity - $discount_amount,
                        ];
                    }
                    break;

                case Consts::SERVICE_TYPE['yearly']:
                    $discount_amount = $this->calculateDiscount($student, $service, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m'),
                        'by_number' => $quantity,
                        'unit_price' => $price,
                        'amount' => $price * $quantity,
                        'discount_amount' => $discount_amount,
                        'final_amount' => $price * $quantity - $discount_amount,
                    ];
                    break;

                case Consts::SERVICE_TYPE['once']:
                    $discount_amount = $this->calculateDiscount($student, $service, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m'),
                        'by_number' => $quantity,
                        'unit_price' => $price,
                        'amount' => $price * $quantity,
                        'discount_amount' => $discount_amount,
                        'final_amount' => $price * $quantity - $discount_amount,
                    ];
                    break;

                case Consts::SERVICE_TYPE['auto_cancel']:
                    $discount_amount = $this->calculateDiscount($student, $service, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m'),
                        'by_number' => $quantity,
                        'unit_price' => $price,
                        'amount' => $price * $quantity,
                        'discount_amount' => $discount_amount,
                        'final_amount' => $price * $quantity - $discount_amount,
                    ];
                    break;
                default:
                    continue; // Không xử lý loại dịch vụ không xác định
            }
        }

        return collect($details);
    }

    /**
     * Lưu phiếu thu và chi tiết.
     */
    protected function saveReceipt(Student $student, $cycle, $details, $data)
    {
        $receipt = Receipt::create([
            'student_id' => $student->id,
            'payment_cycle_id' => $cycle->id,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'receipt_name' => "Phiếu thu kỳ {$cycle->months} tháng",
            'total_amount' => $details->sum('unit_price'),
            'total_discount' => $details->sum('discount'),
            'total_adjustment' => 0,
            'total_final' => $details->sum('total_amount'),
            'total_paid' => 0,
            'total_due' => $details->sum('total_amount'),
            'status' => 'pending',
            'note' => $data['note'] ?? null
        ]);

        foreach ($details as $detail) {
            $receipt->serviceDetail()->create($detail);
        }

        return $receipt;
    }

    /**
     * Tính giảm trừ trên từng dịch vụ.
     */
    protected function calculateDiscount(Student $student, $service, Carbon $startDate = null)
    {
        // Nếu $startDate không tồn tại sẽ tính toán phí theo ưu đãi chính sách


        // Nếu tồn tại $startDate sẽ tính toán phí theo ưu đãi chính sách và theo tháng học sinh nhập học

        return 0;
    }

    /**
     * Kiểm tra có phải kỳ đầu năm học không.
     */
    protected function isFirstCycleOfSchoolYear(Carbon $periodStart): bool
    {
        return $periodStart->month === 6;
    }

    protected function getDiscountParams() : array
    {
        return [
        ];
    }
}
