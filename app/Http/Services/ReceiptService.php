<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Deduction;
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
            $startDate = Carbon::parse($student->enrolled_at);
            $includeCurrent = $data['include_current_month'] ?? true;
            $data['period_start'] = $startDate->copy()->format('Y-m-d');
            if($includeCurrent) {
                $data['period_end'] = $startDate->copy()->addMonths($cycle->months-1)->endOfMonth()->format('Y-m-d');
            }else {
                $data['period_end'] = $startDate->copy()->addMonths($cycle->months)->endOfMonth()->format('Y-m-d');
            }
            
            $details = $this->generateReceiptDetails($student, $cycle, $policies, $deductions, $data['services'], $startDate, $includeCurrent);

            return $this->saveReceipt($student, $cycle, $policies, $details, $data);
        });
    }

    /**
     * Sinh các dòng chi tiết biên lai.
     */
    protected function generateReceiptDetails(Student $student, $cycle, $policies, $deductions, $services, Carbon $startDate, bool $includeCurrent)
    {
        $details = [];
        foreach ($services as $service) {
            // Tìm mức chi phí đang áp dụng vào ngày nhập học
            $matchedDetail = collect($service->serviceDetail)->first(function ($detail) use ($startDate) {
                $start = Carbon::parse($detail['start_at']);
                $end = $detail['end_at'] ? Carbon::parse($detail['end_at']) : null;

                return $startDate->gte($start) && ($end === null || $startDate->lte($end));
            });

            $service_info['id'] = $service->id;
            $service_info['price'] = $matchedDetail['price'] ?? 0;
            $service_info['quantity'] = $matchedDetail['quantity'] ?? 0;
            switch ($service->service_type) {
                case Consts::SERVICE_TYPE['monthly']:
                    $monthCount = $cycle->months;
                    $firstMonth = $startDate->copy();
                    // Ở đây cần xử lý trường hợp đặc biệt dành cho tháng hiện tại (nhập học)
                    $discount_amount = $this->calculateDiscount($student, $service_info, $cycle, $policies, $deductions, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $firstMonth->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount,
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount,
                    ];

                    // Thêm tháng vừa tính
                    $firstMonth->addMonth();
                    // Nếu không bao gồm tháng hiện tại thì giảm số tháng đi 1
                    if (!$includeCurrent) {
                        $monthCount -= 1;
                    }
                    // Tính toán các tháng còn lại
                    for ($i = 0; $i < $monthCount; $i++) {
                        $month = $firstMonth->copy()->addMonths($i);
                        $discount_amount = $this->calculateDiscount($student, $service_info, $cycle, $policies, $deductions);
                        $details[] = [
                            'service_id' => $service->id,
                            'month' => $month->startOfMonth()->format('Y-m-d'),
                            'by_number' => $service_info['quantity'],
                            'unit_price' => $service_info['price'],
                            'amount' => $service_info['price'] * $service_info['quantity'],
                            'discount_amount' => $discount_amount,
                            'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount,
                        ];
                    }
                    break;

                case Consts::SERVICE_TYPE['yearly']:
                    $discount_amount = $this->calculateDiscount($student, $service_info, $cycle, $policies, $deductions, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount,
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount,
                    ];
                    break;

                case Consts::SERVICE_TYPE['once']:
                    $discount_amount = $this->calculateDiscount($student, $service_info, $cycle, $policies, $deductions, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount,
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount,
                    ];
                    break;

                case Consts::SERVICE_TYPE['auto_cancel']:
                    $discount_amount = $this->calculateDiscount($student, $service_info, $cycle, $policies, $deductions, $startDate);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount,
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount,
                    ];
                    break;
                
            }
        }

        return collect($details);
    }

    /**
     * Lưu phiếu thu và chi tiết.
     */
    protected function saveReceipt(Student $student, $cycle, $policies, $details, $data)
    {
        $receipt = Receipt::create([
            'area_id' => $student->area_id,
            'student_id' => $student->id,
            'payment_cycle_id' => $cycle->id,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'receipt_code' => "PT-{$student->id}-{$cycle->id}-" . now()->format('Ymd'),
            'receipt_name' => "Phiếu thu kỳ {$cycle->months} tháng",
            'total_amount' => $details->sum('amount'),
            'total_discount' => $details->sum('discount_amount'),
            'total_adjustment' => 0,
            'total_final' => $details->sum('final_amount'),
            'total_paid' => 0,
            'total_due' => $details->sum('final_amount'),
            'status' => 'pending',
            'note' => $data['note'] ?? null
        ]);

        foreach ($details as $detail) {
            $detail['student_id'] = $student->id;
            $receipt->receiptDetail()->create($detail);
        }

        return $receipt;
    }

    /**
     * Tính giảm trừ trên từng dịch vụ.
     */
    protected function calculateDiscount(Student $student, $service_info, $cycle, $policies, $deductions, ?Carbon $startDate = null)
    {
        $discount_cycle_value = $cycle->json_params->services->{$service_info['id']}->value ?? 0;
        $discount_cycle_type = $cycle->json_params->services->{$service_info['id']}->type ?? null;
        $amount = $service_info['price'] * $service_info['quantity'];
        $amount_after_discount = 0;

        // Ưu đãi theo chu kỳ thanh toán
        if ($discount_cycle_type == Consts::TYPE_POLICIES['percent']) {
            $amount_after_discount = $amount - $amount * ($discount_cycle_value / 100);
        } else if ($discount_cycle_type == Consts::TYPE_POLICIES['fixed_amount']) {
            $amount_after_discount = $amount - $discount_cycle_value;
        }

        // Các ưu đãi theo chính sách (lũy kế)
        foreach ($policies as $policy) {
            $discount_policy_value = $policy->json_params->services->{$service_info['id']}->value ?? 0;
            $discount_policy_type = $policy->json_params->services->{$service_info['id']}->type ?? null;
            if ($discount_policy_type == Consts::TYPE_POLICIES['percent']) {
                $amount_after_discount = $amount_after_discount - $amount_after_discount * ($discount_policy_value / 100);
            } else if ($discount_policy_type == Consts::TYPE_POLICIES['fixed_amount']) {
                $amount_after_discount = $amount_after_discount - $discount_policy_value;
            }
        }

        // Nếu tồn tại $startDate sẽ tính toán phí theo ưu đãi giảm trừ với thời điểm học sinh nhập học
        if ($startDate) {
            foreach ($deductions as $deduction) {
                if ($deduction->condition_type == Consts::CONDITION_TYPE['start_day_range']) {
                    $compare = $startDate->day;
                }
                if ($deduction->condition_type == Consts::CONDITION_TYPE['start_month_range']) {
                    $compare = $startDate->month;
                }
                $start = $deduction->json_params->condition->start ?? null;
                $end = $deduction->json_params->condition->end ?? null;
                if ($compare >= $start && ($end === null || $compare <= $end)) {
                    $deduction_value = $deduction->json_params->services->{$service_info['id']}->value ?? 0;
                    $deduction_type = $deduction->json_params->services->{$service_info['id']}->type ?? null;
                    if ($deduction_type == Consts::TYPE_POLICIES['percent']) {
                        $amount_after_discount = $amount_after_discount - $amount_after_discount * ($deduction_value / 100);
                    } else if ($deduction_type == Consts::TYPE_POLICIES['fixed_amount']) {
                        $amount_after_discount = $amount_after_discount - $deduction_value;
                    }
                }
            }
        }

        return $amount - $amount_after_discount;
    }

    public function getDeductions()
    {
        return Deduction::where('status', Consts::STATUS['active'])->get();
    }
}
