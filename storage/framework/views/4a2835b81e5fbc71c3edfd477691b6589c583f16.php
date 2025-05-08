<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Thu Phí</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            background-color: #d2e4f5;
        }

        .total {
            font-weight: bold;
            background-color: #7ca7d2;
        }

        .footer {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header"><?php echo app('translator')->get('THÔNG BÁO THU PHÍ'); ?></div>

        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Họ và tên'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->first_name ?? ''); ?> <?php echo e($detail->student->last_name ?? ''); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Lớp học'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo app('translator')->get('Chưa cập nhật'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Ngày sinh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : ''); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Mã học sinh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->student_code); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Tên phụ huynh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <?php if(isset($detail->student->studentParents)): ?>
                            <?php $__currentLoopData = $detail->student->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($item->relationship->title ?? ''); ?>: <?php echo e($item->parent->first_name ?? ''); ?>

                                    <?php echo e($item->parent->last_name ?? ''); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>TT</th>
                    <th>CHI TIẾT CÁC KHOẢN PHÍ</th>
                    <th>Số tiền</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>I</td>
                    <td>CÁC KHOẢN THU PHÍ ĐẦU NĂM</td>
                    <td>6,484,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Phí phát triển trường/cơ sở vật chất</td>
                    <td>2,000,000</td>
                    <td>Kỳ 2 năm học 2024-2025 từ 01/01/2025 - 31/05/2025</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Phí ghi danh</td>
                    <td>2,000,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Phí đồng phục</td>
                    <td>450,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Phí học liệu</td>
                    <td>1,284,000</td>
                    <td>Kỳ 2 năm học 2024-2025 từ 01/01/2025 - 31/05/2025</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Học phí học tiếng Anh Tăng Cường</td>
                    <td>750,000</td>
                    <td>Kỳ 2 năm học 2024-2025 từ 01/01/2025 - 31/05/2025</td>
                </tr>

                <tr>
                    <td>II</td>
                    <td>CÁC KHOẢN PHÍ THU THEO KỲ</td>
                    <td>84,150,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Học phí học tiếng Anh Tăng Cường 12 tháng</td>
                    <td>79,500,000</td>
                    <td>Từ 01/04/2025 - 31/03/2026</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Học phí ăn trưa (chính thức 5 tháng)</td>
                    <td>4,650,000</td>
                    <td>Từ 01/04/2025 - 31/08/2025</td>
                </tr>

                <tr>
                    <td>III</td>
                    <td>CÁC KHOẢN PHÍ KHÁC</td>
                    <td>108,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Phí chụp ảnh thẻ</td>
                    <td>108,000</td>
                    <td></td>
                </tr>

                <tr>
                    <td>IV</td>
                    <td><?php echo app('translator')->get('Các khoản giải trình'); ?></td>
                    <td><?php echo e(number_format($detail->prev_balance ?? 0, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <?php if(isset($detail->json_params->explanation) && count((array)$detail->json_params->explanation) > 0): ?>
                    <?php $__currentLoopData = $detail->json_params->explanation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>IV.<?php echo e($loop->index + 1); ?></td>
                            <td><?php echo e($item->content ?? ''); ?></td>
                            <td><?php echo e(number_format($item->value ?? '', 0, ',', '.')); ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endif; ?>


                <tr>
                    <td colspan="2"><?php echo app('translator')->get('TỔNG PHẢI NỘP'); ?> (I + II + III - IV)</td>
                    <td><?php echo e(number_format($detail->total_final + $detail->prev_balance, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN ĐÃ NỘP'); ?></td>
                    <td><?php echo e(number_format($detail->total_paid ?? 0, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN CÒN PHẢI NỘP'); ?></td>
                    <td><?php echo e(number_format($detail->total_due, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Hình thức thanh toán:</strong></p>
            <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
            <p><strong>Tên TK:</strong> Công ty Cổ phần Mầm Non STEAME GARTEN</p>
            <p><strong>Số TK:</strong> 2662686868 - Techcombank - Chi nhánh Hà Thành - Hà Nội</p>
            <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Ngày sinh</p>
            <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
        </div>
    </div>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/print.blade.php ENDPATH**/ ?>