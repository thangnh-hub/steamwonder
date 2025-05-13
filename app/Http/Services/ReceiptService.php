<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Deduction;
use App\Models\Student;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptService
{
    /**
     * Tạo phiếu thu mới cho học sinh.
     */

    protected $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    //Check xem học sinh đã có dịch vụ nào trong biên lai chưa
    // Nếu có thì không cho phép tạo biên lai mới
    public function checkExistingServiceInReceipts(Student $student, array $serviceIds)
    {
        $existing = ReceiptDetail::where('student_id', $student->id)
        ->whereIn('service_id', $serviceIds)->exists();
        return $existing;
    }

    public function createReceiptForStudent(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            $policies = $student->studentPolicies->pluck('policy');
            $promotions = $student->studentPromotions;
            $deductions = $this->getDeductions();
            $startDate = Carbon::parse($data['enrolled_at']);
            $includeCurrent = $data['include_current_month'] ?? true;
            $details = $this->generateReceiptDetails($policies, $promotions,$deductions, $data['student_services'], $startDate, $includeCurrent);
            
            return $this->saveReceipt($student,  $details, $data);
        });
    }

    /**
     * Sinh các dòng chi tiết biên lai.
     */
    protected function generateReceiptDetails( $policies,$promotions , $deductions, $student_services, Carbon $startDate, bool $includeCurrent)
    {
        $details = [];
        foreach ($student_services as $studentservice) {
            $cycle= $studentservice->paymentcycle;
            $service = $studentservice->services;
            // Tìm mức chi phí đang áp dụng vào ngày nhập học
            $matchedDetail = collect($service->serviceDetail)->first(function ($detail) use ($startDate) {
                $start = Carbon::parse($detail['start_at']);
                $end = $detail['end_at'] ? Carbon::parse($detail['end_at']) : null;

                return $startDate->gte($start) && ($end === null || $startDate->lte($end));
            });


            $service_info['id'] = $service->id;
            $service_info['name'] = $service->name??"";
            $service_info['price'] = $matchedDetail['price'] ?? 0;
            $service_info['quantity'] = $matchedDetail['quantity'] ?? 0;
            switch ($service->service_type) {
                case Consts::SERVICE_TYPE['monthly']:
                    $monthCount = $cycle->months;
                    $firstMonth = $startDate->copy();
                    // Ở đây cần xử lý trường hợp đặc biệt dành cho tháng hiện tại (nhập học)
                    $discount_amount = $this->calculateDiscount($service_info, $cycle, $policies, $promotions,$deductions, $startDate, $firstMonth);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $firstMonth->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount['cal_discount_amount'],
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                        'note' => $discount_amount['cal_discount_note'],
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
                        $discount_amount = $this->calculateDiscount( $service_info, $cycle, $policies,$promotions, $deductions, $startDate,$month);
                        $details[] = [
                            'service_id' => $service->id,
                            'month' => $month->startOfMonth()->format('Y-m-d'),
                            'by_number' => $service_info['quantity'],
                            'unit_price' => $service_info['price'],
                            'amount' => $service_info['price'] * $service_info['quantity'],
                            'discount_amount' => $discount_amount['cal_discount_amount'],
                            'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                            'note' => $discount_amount['cal_discount_note'],
                        ];
                    }
                    break;

                case Consts::SERVICE_TYPE['yearly']:
                    $discount_amount = $this->calculateDiscount( $service_info, $cycle, $policies, $promotions,$deductions, $startDate , $month = null);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount['cal_discount_amount'],
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                        'note' => $discount_amount['cal_discount_note'],
                    ];
                    break;

                case Consts::SERVICE_TYPE['once']:
                    $discount_amount = $this->calculateDiscount($service_info, $cycle, $policies,$promotions, $deductions, $startDate , $month = null);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount['cal_discount_amount'],
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                        'note' => $discount_amount['cal_discount_note'],
                    ];
                    break;

                case Consts::SERVICE_TYPE['auto_cancel']:
                    $discount_amount = $this->calculateDiscount($service_info, $cycle, $policies, $promotions,$deductions, $startDate , $month = null);
                    $details[] = [
                        'service_id' => $service->id,
                        'month' => $startDate->format('Y-m-d'),
                        'by_number' => $service_info['quantity'],
                        'unit_price' => $service_info['price'],
                        'amount' => $service_info['price'] * $service_info['quantity'],
                        'discount_amount' => $discount_amount['cal_discount_amount'],
                        'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                        'note' => $discount_amount['cal_discount_note'],
                    ];
                    break;
            }
        }
        return collect($details);
    }

    /**
     * Lưu phiếu thu và chi tiết.
     */
    protected function saveReceipt(Student $student, $details, $data)
    {
        
        $receipt = Receipt::create([
            'area_id' => $student->area_id,
            'student_id' => $student->id,
            'receipt_code' => "PT-{$student->id}-" . now()->format('Ymd'),
            'receipt_name' => "Phiếu thu HSM {$student->first_name} {$student->last_name}",
            'total_amount' => $details->sum('amount'),
            'total_discount' => $details->sum('discount_amount'),
            'total_adjustment' => 0,
            'total_final' => $details->sum('final_amount'),
            'total_paid' => 0,
            'total_due' => $details->sum('final_amount'),
            'status' => 'pending',
            'admin_created_id' => $this->admin->id,
            'note' => $data['note'] ?? null
        ]);

        foreach ($details as $detail) {
            $detail['student_id'] = $student->id;
            $detail['admin_created_id'] = $this->admin->id;
            $receipt->receiptDetail()->create($detail);
        }

        return $receipt;
    }

    /**
     * Tính giảm trừ trên từng dịch vụ.
     */
    protected function calculateDiscount( $service_info, $cycle, $policies, $promotions, $deductions, ?Carbon $startDate = null, $month = null)
    {
        $discount_cycle_value = $cycle->json_params->services->{$service_info['id']}->value ?? 0;
        $discount_cycle_type = $cycle->json_params->services->{$service_info['id']}->type ?? null;
        $amount = $service_info['price'] * $service_info['quantity'];
        $amount_after_discount = $amount;
        $discount_notes = [];
        $service_name = $service_info['name'];
        // Kiểm tra có chương trình khuyến mãi nào đc áp dụng không
        $has_valid_promotion = false;
        
        // Ưu đãi theo khuyến mãi hợp lệ
        foreach ($promotions as $pro) {
            $start = Carbon::parse($pro->time_start)->startOfMonth();
            $end = Carbon::parse($pro->time_end)->endOfMonth();
            $checkMonth = Carbon::parse($month)->startOfMonth();
          
            if ($checkMonth->between($start, $end)) {
                $discount_promotion_value = $pro->promotion->json_params->services->{$service_info['id']}->value ?? 0;
                $discount_promotion_type = $pro->promotion->promotion_type ?? null;
    
                if ($discount_promotion_type == Consts::TYPE_POLICIES['percent'] && $discount_promotion_value > 0) {
                    $has_valid_promotion = true;
                    $discount_notes[] = "{$pro->promotion->promotion_name} giảm ({$discount_promotion_value}%)";
                    $amount_after_discount = $amount_after_discount - $amount_after_discount * ($discount_promotion_value / 100);
                }    
            }
            
        }

        // Ưu đãi theo chu kỳ thanh toán
        if (!$has_valid_promotion) {
            if ($discount_cycle_type == Consts::TYPE_POLICIES['percent']) {
                $discount_notes[] = "Chu kỳ thanh toán {$cycle->name} - {$service_name} giảm: ({$discount_cycle_value}%)";
                $amount_after_discount = $amount - $amount * ($discount_cycle_value / 100);
            } else if ($discount_cycle_type == Consts::TYPE_POLICIES['fixed_amount']) {
                $discount_notes[] = "Chu kỳ thanh toán {$cycle->name} - {$service_name} (giảm:" . number_format($discount_cycle_value) . "đ)";
                $amount_after_discount = $amount - $discount_cycle_value;
            }
        }

        // Các ưu đãi theo chính sách (lũy kế)
        foreach ($policies as $policy) {
            $discount_policy_value = $policy->json_params->services->{$service_info['id']}->value ?? 0;
            $discount_policy_type = $policy->json_params->services->{$service_info['id']}->type ?? null;
            if ($discount_policy_type == Consts::TYPE_POLICIES['percent']) {
                $discount_notes[] = "Chính sách {$policy->name} giảm ({$discount_policy_value}%)";
                $amount_after_discount = $amount_after_discount - $amount_after_discount * ($discount_policy_value / 100);
            } else if ($discount_policy_type == Consts::TYPE_POLICIES['fixed_amount']) {
                $discount_notes[] = "Chính sách {$policy->name} giảm (" . number_format($discount_policy_value) . "đ)";
                $amount_after_discount = $amount_after_discount - $discount_policy_value;
            }
        }

        // Nếu tồn tại $startDate sẽ tính toán phí theo ưu đãi giảm trừ với thời điểm học sinh nhập học
        // Cần bổ sung ở đây để kiểm tra việc học sinh là nhập học mới hay là gia hạn
        // Nếu là gia hạn thì không cần tính toán lại ưu đãi giảm trừ
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
                        $discount_notes[] = "Giảm trừ {$deduction->name} giảm ({$deduction_value}%)";
                        $amount_after_discount = $amount_after_discount - $amount_after_discount * ($deduction_value / 100);
                    } else if ($deduction_type == Consts::TYPE_POLICIES['fixed_amount']) {
                        $discount_notes[] = "Giảm trừ {$deduction->name} giảm (" . number_format($deduction_value) . "đ)";
                        $amount_after_discount = $amount_after_discount - $deduction_value;
                    }
                }
            }
        }
        $discount_amount = $amount - $amount_after_discount;
        
        return [
            'cal_discount_amount' => $discount_amount,
            'cal_discount_note' => "" . implode('<br>', $discount_notes)
        ];
    }

    public function getDeductions()
    {
        return Deduction::where('status', Consts::STATUS['active'])->get();
    }

    //PHẦN TÁI TỤC

    public function renewReceiptForStudent(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            $policies = $student->studentPolicies->pluck('policy');
            $promotions = $student->studentPromotions;
            $startDate = Carbon::parse($data['enrolled_at']);
            
            $details = $this->generateReceiptDetailsRenew( $policies,$promotions,  $data['student_services'], $startDate);
            return $this->saveReceiptRenew($student, $details, $data);
        });
    }

    protected function generateReceiptDetailsRenew( $policies, $promotions, $student_services, Carbon $startDate )
    {
        $details = [];
        foreach ($student_services as $studentservice) {
            $cycle= $studentservice->paymentcycle;
            $service = $studentservice->services;
            // Tìm mức chi phí đang áp dụng vào ngày nhập học
            $matchedDetail = collect($service->serviceDetail)->first(function ($detail) use ($startDate) {
                $start = Carbon::parse($detail['start_at']);
                $end = $detail['end_at'] ? Carbon::parse($detail['end_at']) : null;

                return $startDate->gte($start) && ($end === null || $startDate->lte($end));
            });

            $service_info['id'] = $service->id;
            $service_info['name'] = $service->name??"";
            $service_info['price'] = $matchedDetail['price'] ?? 0;
            $service_info['quantity'] = $matchedDetail['quantity'] ?? 0;
            switch ($service->service_type) {
                case Consts::SERVICE_TYPE['monthly']:
                    $monthCount = $cycle->months;
                    $firstMonth = $startDate->copy();
                    // Tính toán các tháng còn lại
                    for ($i = 0; $i < $monthCount; $i++) {
                        $month = $firstMonth->copy()->addMonths($i);
                        $discount_amount = $this->calculateDiscountRenew( $service_info, $cycle, $policies, $promotions,$month) ;
                        $details[] = [
                            'service_id' => $service->id,
                            'month' => $month->startOfMonth()->format('Y-m-d'),
                            'by_number' => $service_info['quantity'],
                            'unit_price' => $service_info['price'],
                            'amount' => $service_info['price'] * $service_info['quantity'],
                            'discount_amount' => $discount_amount['cal_discount_amount'],
                            'final_amount' => $service_info['price'] * $service_info['quantity'] - $discount_amount['cal_discount_amount'],
                            'note' => $discount_amount['cal_discount_note'],
                        ];
                    }
                break;
            }
        }

        return collect($details);
    }

    protected function calculateDiscountRenew($service_info, $cycle, $policies , $promotions, $month)
    {
        $discount_cycle_value = $cycle->json_params->services->{$service_info['id']}->value ?? 0;
        $discount_cycle_type = $cycle->json_params->services->{$service_info['id']}->type ?? null;
        $amount = $service_info['price'] * $service_info['quantity'];
        $amount_after_discount = $amount;
        $discount_notes = [];
        $service_name = $service_info['name'];

        // Kiểm tra có chương trình khuyến mãi nào đc áp dụng không
        $has_valid_promotion = false;
        
        // Ưu đãi theo khuyến mãi hợp lệ
        foreach ($promotions as $pro) {
            $start = Carbon::parse($pro->time_start)->startOfMonth();
            $end = Carbon::parse($pro->time_end)->endOfMonth();
            $checkMonth = Carbon::parse($month)->startOfMonth();

            if ($checkMonth->between($start, $end)) {
                $discount_promotion_value = $pro->promotion->json_params->services->{$service_info['id']}->value ?? 0;
                $discount_promotion_type = $pro->promotion->promotion_type ?? null;
    
                if ($discount_promotion_type == Consts::TYPE_POLICIES['percent'] && $discount_promotion_value > 0) {
                    $has_valid_promotion = true;
                    $discount_notes[] = "{$pro->promotion->promotion_name} giảm ({$discount_promotion_value}%)";
                    $amount_after_discount = $amount_after_discount - $amount_after_discount * ($discount_promotion_value / 100);
                }    
            }
            
        }
        // Ưu đãi theo chu kỳ thanh toán
        if (!$has_valid_promotion) {
            if ($discount_cycle_type == Consts::TYPE_POLICIES['percent']) {
                $discount_notes[] = "Chu kỳ thanh toán {$cycle->name} - {$service_name} giảm: ({$discount_cycle_value}%)";
                $amount_after_discount = $amount - $amount * ($discount_cycle_value / 100);
            } else if ($discount_cycle_type == Consts::TYPE_POLICIES['fixed_amount']) {
                $discount_notes[] = "Chu kỳ thanh toán {$cycle->name} - {$service_name} (giảm:" . number_format($discount_cycle_value) . "đ)";
                $amount_after_discount = $amount - $discount_cycle_value;
            }
        }

        // Các ưu đãi theo chính sách (lũy kế)
        foreach ($policies as $policy) {
            $discount_policy_value = $policy->json_params->services->{$service_info['id']}->value ?? 0;
            $discount_policy_type = $policy->json_params->services->{$service_info['id']}->type ?? null;
            if ($discount_policy_type == Consts::TYPE_POLICIES['percent']) {
                $discount_notes[] = "Chính sách {$policy->name} giảm ({$discount_policy_value}%)";
                $amount_after_discount = $amount_after_discount - $amount_after_discount * ($discount_policy_value / 100);
            } else if ($discount_policy_type == Consts::TYPE_POLICIES['fixed_amount']) {
                $discount_notes[] = "Chính sách {$policy->name} giảm (" . number_format($discount_policy_value) . "đ)";
                $amount_after_discount = $amount_after_discount - $discount_policy_value;
            }
        }


        $discount_amount = $amount - $amount_after_discount;
        return [
            'cal_discount_amount' => $discount_amount,
            'cal_discount_note' => "" . implode('<br>', $discount_notes)
        ];
    }

    protected function saveReceiptRenew(Student $student, $details, $data)
    {
        $startDate = Carbon::parse($data['enrolled_at']);
        $receipt = Receipt::create([
            'area_id' => $student->area_id,
            'student_id' => $student->id,
            'receipt_code' => "PT-{$student->student_code}-" . $startDate->copy()->format('Ymd'),
            'receipt_name' => "Phiếu tái tục: {$student->first_name} {$student->last_name} ({$student->student_code})",
            'total_amount' => $details->sum('amount'),
            'total_discount' => $details->sum('discount_amount'),
            'total_adjustment' => 0,
            'total_final' => $details->sum('final_amount'),
            'total_paid' => 0,
            'total_due' => $details->sum('final_amount'),
            'status' => 'pending',
            'admin_created_id' => $this->admin->id,
            'note' => $data['note'] ?? null
        ]);

        foreach ($details as $detail) {
            $detail['student_id'] = $student->id;
            $detail['admin_created_id'] = $this->admin->id;
            $receipt->receiptDetail()->create($detail);
        }

        $receipt->receipt_code = "PT-{$receipt->id}-" . $startDate->copy()->format('Ymd');
        $receipt->save();

        return $receipt;
    }

}
