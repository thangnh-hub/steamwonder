<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Thu Phí</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            margin: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .wrapper {
            max-width: 297mm;
            margin: 0 auto;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .info {
            width: 100%;
            margin-bottom: 10px;
        }

        .fee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .fee-table th,
        .fee-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .fee-table th {
            background-color: #e9f0f8;
            text-align: center;
        }

        .section {
            background-color: #dce6f1;
            font-weight: bold;
        }

        .total {
            background-color: #c5d9f1;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            display: flex;
        }

        .bank-info {
            width: 70%;
        }

        .qr-code {
            width: 30%;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        @media  print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .fee-table th,
            .section,
            .total {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page  {
                size: A4 portrait;
                margin: 0;
            }

            .qr-code img {
                width: 100%;
                height: auto;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <h2 class="title">THÔNG BÁO THU PHÍ</h2>

        <table class="info">
            <tr>
                <td>
                    <strong>Họ và tên:</strong>
                    <?php echo e(optional($detail->student)->first_name ?? ''); ?>

                    <?php echo e(optional($detail->student)->last_name ?? ''); ?>

                </td>
                <td>
                    <strong>Lớp học:</strong>
                    <?php echo e(optional(optional($detail->student)->currentClass)->name ?? ''); ?>

                </td>
            </tr>
            <tr>
                <td>
                    <strong>Ngày sinh:</strong>
                    <?php echo e($detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : ''); ?>

                </td>
                <td>
                    <strong>Phụ huynh:</strong>
                    <?php if(isset(optional($detail->student)->studentParents)): ?>
                        <?php $__currentLoopData = optional($detail->student)->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(optional($item->relationship)->title ?? ''); ?> <?php echo e(optional($item->parent)->first_name ?? ''); ?>

                            <?php echo e(optional($item->parent)->last_name . '. ' ?? ''); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Mã học sinh:</strong>
                    <?php echo e(optional($detail->student)->student_code); ?>

                </td>
                <td>
                    <strong>Mã TBP:</strong>
                    <?php echo e($detail->receipt_code ?? ''); ?>

                </td>
            </tr>
        </table>

        <table class="fee-table" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>TT</th>
                    <th>CHI TIẾT CÁC KHOẢN PHÍ</th>
                    <th>SỐ TIỀN</th>
                    <th>GHI CHÚ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                ?>
                
                <?php if(count($serviceYearly) > 0): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td class="text-uppercase"><?php echo app('translator')->get('Các khoản thu đầu năm'); ?></td>
                        <td><?php echo e(number_format($serviceYearly['total_amount'] ?? 0, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceYearly['services']) && count($serviceYearly['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceYearly['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?></td>
                                <td>
                                    Từ:
                                    <?php echo e(\Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? ''); ?>

                                    -
                                    <?php echo e(\Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                
                <?php if(count($serviceMonthly) > 0): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td class="text-uppercase"><?php echo app('translator')->get('Các khoản thu theo kỳ'); ?></td>
                        <td class="text-right"><?php echo e(number_format($serviceMonthly['total_amount'] ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceMonthly['services']) && count($serviceMonthly['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceMonthly['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?>

                                </td>
                                <td>
                                    Từ:
                                    <?php echo e(\Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? ''); ?>

                                    -
                                    <?php echo e(\Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                
                <?php if(count($serviceOther) > 0): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td class="text-uppercase"><?php echo app('translator')->get('Các khoản thu phí khác'); ?></td>
                        <td class="text-right"><?php echo e(number_format($serviceOther['total_amount'] ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceOther['services']) && count($serviceOther['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceOther['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?>

                                </td>
                                <td>
                                    Từ:
                                    <?php echo e(\Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? ''); ?>

                                    -
                                    <?php echo e(\Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                
                <?php if(count($listServiceDiscount) > 0): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td class="text-uppercase"><?php echo app('translator')->get('Các khoản Ưu đãi/Giảm trừ'); ?></td>
                        <td class="text-right">
                            <?php echo e(number_format($listServiceDiscount->sum('total_discount_amount') ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($listServiceDiscount) && count($listServiceDiscount) > 0): ?>
                        <?php $__currentLoopData = $listServiceDiscount; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right">
                                    <?php echo e(number_format($item['total_discount_amount'] ?? 0, 0, ',', '.')); ?></td>
                                <td><?php echo $item['note']; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>

                <?php if(
                    $detail->prev_balance != 0 ||
                        (isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0)): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('CÁC KHOẢN TRUY THU/HOÀN TRẢ'); ?> [+ Có , - Nợ]</td>
                        <td class="text-right"><?php echo e(number_format($detail->prev_balance ?? 0, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                    <?php if(isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0): ?>
                        <?php $__currentLoopData = $detail->json_params->explanation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item->content ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item->value ?? 0, 0, ',', '.')); ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG PHẢI NỘP'); ?> </td>
                    <td class="text-right">
                        <?php echo e(number_format($detail->total_final, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN ĐÃ NỘP'); ?></td>
                    <td class="text-right"><?php echo e(number_format($detail->total_paid ?? 0, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN CÒN PHẢI NỘP'); ?></td>
                    <td class="text-right"><?php echo e(number_format($detail->total_due, 0, ',', '.')); ?>

                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="bank-info">
                <p><strong>Hình thức thanh toán:</strong></p>
                <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
                <p><strong>Tên TK:</strong> <?php echo e(optional($detail->area)->json_params->bank_account ?? ''); ?></p>
                <p><strong>Số TK:</strong> <?php echo e(optional($detail->area)->json_params->bank_stk ?? ''); ?> -
                    <?php echo e(optional($detail->area)->json_params->bank_name ?? ''); ?></p>
                <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Mã TBP</p>
                <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
            </div>
            <?php if(isset($qrCode)): ?>
                <div class="qr-code">
                    <p class="text-center"><img src="<?php echo e($qrCode); ?>" alt="QR Ngân hàng" width="250"></p>
                    <p class="text-center"><?php echo app('translator')->get('Vui lòng quét mã QR để thanh toán'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mở hộp thoại in
            // window.print();
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/receipt/print.blade.php ENDPATH**/ ?>