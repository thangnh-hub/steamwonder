<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('themes/frontend/education/plugins/font-awesome-4.7.0/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href=" https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,600,700,800,900">
    <title>Thông Báo Thu Phí</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            margin: 0 20px;
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
            /* margin-top: 25px;
            border-top: 1px dashed #000;
            padding-top: 10px; */
            display: flex;
            /* position: relative; */
        }

        .bank-info {
            width: 80%;
            text-align: justify;
        }

        .content-payment {
            text-align: justify;
        }

        .qr-code {
            width: 20%;
            /* position: absolute;
            top: 0;
            right: 0; */
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .btn {
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .btn {
            border-radius: 3px;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 1px solid transparent;
        }

        .btn-success {
            color: #fff;
            background-color: #00a65a;
            border-color: #008d4c;
            position: fixed;
            top: 50px;
            right: 30px;
        }

        @media  print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .btn-success {
                display: none;
            }

            .fee-table th,
            .section,
            .total {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page  {
                size: A4 portrait;
                margin: 10px 0 0 0;
            }

            .qr-code img {
                /* position: absolute;
                top: 0; */
                width: 100%;
                height: auto;
            }

            .page-break {
                page-break-before: always;
                break-before: page;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            width: 20%;
            float: left;
        }

        .header .logo img {
            max-width: 100%;
            height: auto;
            width: 150px;
        }

        .header .company-info {
            width: 80%;
            float: left;
            margin-left: 20px;
            margin-right: 20px;
        }

        .qr-code img {
            width: 100%;
            height: auto;
        }

        p {
            margin-top: 0px;
        }

        .sub-title {
            border-bottom: solid 1px #999999;
            padding-bottom: 1px;
        }

        .header img {
            width: 100%;
            height: auto;
        }

        h3 {
            margin-bottom: 0.5em;
        }

        .color-title {
            color: #1169b7;
        }
    </style>
</head>
<?php
    $month = \Carbon\Carbon::parse($detail->period_start)->format('m/Y');
?>

<body>

    <div class="wrapper">
        <button class="btn btn-success btn-print"><i class="fa fa-print" aria-hidden="true"></i> <?php echo app('translator')->get('In TBP'); ?>
        </button>
        <div class="header">
            <img src="https://steamwonders.vn/data/logo/header.jpg" alt="Banner-header" srcset="">
            
        </div>

        <h2 class="title color-title">
            THÔNG BÁO THU PHÍ THÁNG <?php echo e($month); ?>

        </h2>

        <div class="content">
            <p style="margin-top: 1em;"><i class="sub-title">Kính gửi:</i> <strong>Quý Phụ huynh,</strong></p>
            <p>
                Lời đầu tiên, <?php echo e(optional($detail->area)->json_params->school ?? ''); ?> trân trọng cảm ơn Quý Phụ huynh
                đã quan tâm trong suốt thời gian qua.
            </p>
            <p>
                Để thuận tiện cho công tác thu học phí tháng <?php echo e($month); ?>, Nhà trường xin gửi tới Quý Phụ huynh
                <strong>Thông báo thu phí chi tiết </strong>kèm theo. Quý Phụ huynh vui lòng theo dõi và hoàn tất các
                khoản phí theo thời hạn được ghi rõ trong thông báo.
            </p>
        </div>

        <h3 class="color-title">1. THÔNG TIN HỌC SINH</h3>

        <table class="info">
            <tr>
                <td>
                    - Mã học sinh:
                    <strong><?php echo e(optional($detail->student)->student_code); ?></strong>
                </td>
            </tr>
            <tr>
                <td>
                    - Họ và tên học sinh:
                    <strong><?php echo e(optional($detail->student)->first_name ?? ''); ?>

                        <?php echo e(optional($detail->student)->last_name ?? ''); ?></strong>
                </td>
            </tr>
            <tr>
                <td>
                    - Ngày sinh:
                    <strong><?php echo e($detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : ''); ?></strong>
                </td>
            </tr>
            <tr>
                <td>
                    - Lớp học:
                    <strong>
                        <?php echo e(optional(optional($detail->student)->currentClass)->name ?? ''); ?>

                        -
                        <?php echo e(optional(optional(optional($detail->student)->currentClass)->education_programs)->name ?? ''); ?>

                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    - Phụ huynh:
                    <strong>
                        <?php if(isset(optional($detail->student)->studentParents)): ?>
                            <?php $__currentLoopData = optional($detail->student)->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(optional($item->relationship)->title ?? ''); ?>

                                <?php echo e(optional($item->parent)->first_name ?? ''); ?>

                                <?php echo e(optional($item->parent)->last_name ?? ''); ?>

                                (<?php echo e(optional($item->parent)->phone ?? ''); ?>)
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    - Mã phiếu thông báo phí:
                    <strong><?php echo e($detail->receipt_code ?? ''); ?></strong>
                </td>
            </tr>
        </table>

        <h3 class="color-title">2. CÁC KHOẢN PHÍ PHẢI NỘP (Tạm tính)</h3>
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
                        <td class="text-right"><?php echo e(number_format($serviceYearly['total_amount'] ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceYearly['services']) && count($serviceYearly['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceYearly['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?>

                                </td>
                                <td>
                                    Năm học: <?php echo e(\App\Helpers::getYear($item['min_month'])); ?>

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
                                <td>
                                    <?php if($item['service_type'] == 'yearly'): ?>
                                        Năm học: <?php echo e(\App\Helpers::getYear($item['min_month'])); ?>

                                    <?php elseif($item['service_type'] == 'monthly'): ?>
                                        Từ:
                                        <?php echo e(\Carbon\Carbon::parse($item['min_month'])->copy()->format('d/m/Y') ?? ''); ?>

                                        -
                                        <?php echo e(\Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? ''); ?>

                                        <?php echo $item['note']; ?>

                                </td>
                        <?php endif; ?>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <?php $i++; ?>
                <?php endif; ?>

                <?php if($detail->prev_balance != 0 || isset($detail->student->receiptAdjustment)): ?>
                    <tr class="section">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('CÁC KHOẢN TRUY THU/HOÀN TRẢ'); ?> [+ Có , - Nợ]</td>
                        <td class="text-right"><?php echo e(number_format($detail->prev_balance ?? 0, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                    <?php if(isset($detail->student->receiptAdjustment) && count((array) $detail->student->receiptAdjustment) > 0): ?>
                        <?php $__currentLoopData = $detail->student->receiptAdjustment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item->note ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format(abs($item->final_amount ?? 0), 0, ',', '.')); ?>

                                </td>
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
        
        <h3 class="color-title">3. HƯỚNG DẪN THANH TOÁN</h3>
        <div class="footer">
            <div class="bank-info">
                <p><strong class="sub-title">Phương thức thanh toán:</strong></p>
                <p>Quý Phụ huynh vui lòng chuyển khoản vào tài khoản của Nhà trường bằng cách quét mã QR dưới đây hoặc
                    chuyển khoản theo thông tin sau:</p>
                <p>- Tên TK: <strong><?php echo e(optional($detail->area)->json_params->bank_account ?? ''); ?></strong></p>
                <p>- Số TK:
                    <strong><?php echo e(optional($detail->area)->json_params->bank_stk ?? ''); ?> -
                        <?php echo e(optional($detail->area)->json_params->bank_name ?? ''); ?>

                    </strong>
                </p>
                <p>Nội dung chuyển khoản: <strong>Mã học sinh_Tên học sinh_Mã phiếu thông báo phí</strong></p>
            </div>
            <?php if(isset($qrCode)): ?>
                <div class="qr-code">
                    <img src="<?php echo e($qrCode); ?>" alt="QR Ngân hàng">
                </div>
            <?php endif; ?>
        </div>
        <div class="content-payment">
            <p><strong class="sub-title">Thời hạn và hướng dẫn thanh toán:</strong></p>
            <p>
                <strong><i class="fa fa-clock-o"></i> Thời hạn nộp phí:</strong><br />
                Quý Phụ huynh vui lòng hoàn tất thanh toán trong vòng 10 ngày kể từ khi nhận được thông báo này.
            </p>
            <p>
                <strong><i class="fa fa-money"></i> Thanh toán phí:</strong><br />
                Việc thanh toán học phí là nghĩa vụ tài chính của Phụ huynh. Trong trường hợp vì bất kỳ lý do nào mà
                thông báo nhắc phí không đến được, điều này không làm thay đổi trách nhiệm thanh toán đúng hạn.
            </p>
            <p>
                Nếu quá thời hạn nộp phí, tổng số tiền chưa thanh toán sẽ bị tính lãi 0,05%/ngày chậm và Phụ huynh sẽ
                không được hưởng các chính sách ưu đãi liên quan.
            </p>
            <p>
                <strong><i class="fa fa-bolt"></i> Lưu ý:</strong>
                Nhà trường có quyền không xếp lớp hoặc tạm dừng cung cấp dịch vụ học tập nếu học phí không được thanh
                toán đúng hạn theo quy định.
            </p>
            <p>
                Nếu Quý Phụ huynh đã hoàn tất thanh toán, vui lòng bỏ qua thông báo này. Mọi thắc mắc xin liên hệ bộ
                phận CSKH theo địa chỉ email trong thông báo để được hỗ trợ.
            </p>
            <p><strong>Thông báo này được gửi tự động từ hệ thống, không có chữ ký và con dấu.</strong></p>
            <p>
                Mọi thắc mắc xin vui lòng liên hệ Bộ phận CSKH của cơ sở hoặc gửi email về địa chỉ: <br />
                <strong><?php echo e(optional($detail->area)->json_params->email ?? ''); ?></strong><br />
                <strong>Hotline: <?php echo e(optional($detail->area)->json_params->phone ?? ''); ?></strong>
            </p>
        </div>
        <h3 class="color-title">
            Trân trọng cảm ơn!<br />
            STEAME WONDERS - ƯƠM MẦM SÁNG TẠO - MỞ LỐI TƯƠNG LAI!

        </h3>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-print').click(function() {
                window.print();
            })
            // Mở hộp thoại in
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/print.blade.php ENDPATH**/ ?>