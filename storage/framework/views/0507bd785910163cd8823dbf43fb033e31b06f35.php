<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-success pull-right " href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
            </a>
            <a class="btn btn-warning pull-right mr-10" href="<?php echo e(route(Request::segment(2) . '.print', $detail->id)); ?>">
                <i class="fa fa-print"></i> <?php echo app('translator')->get('In hóa đơn'); ?>
            </a>
        </h1>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">


        <div class="box">
            <div class="box-header">
                
            </div>
            <div class="box-body box_alert">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('errorMessage')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('successMessage')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="custom-scroll table-responsive">
                            <h3>Thông tin học sinh</h3>
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th>Mã học sinh</th>
                                        <th>Họ tên</th>
                                        <th>Nickname</th>
                                        <th>Ngày sinh</th>
                                        <th>Địa chỉ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b><?php echo e($detail->student->student_code ?? ''); ?></b></td>
                                        <td><b><?php echo e($detail->student->first_name ?? ''); ?>

                                                <?php echo e($detail->student->last_name ?? ''); ?></b></td>
                                        <td><?php echo e($detail->student->nickname ?? ''); ?></td>
                                        <td><?php echo e(isset($detail->student->birthday) && $detail->student->birthday != '' ? date('d-m-Y', strtotime($detail->student->birthday)) : ''); ?>

                                        </td>
                                        <td><?php echo e($detail->student->address ?? ''); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-scroll table-responsive">
                            <table id="dt_basic" class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="text-left"><b>1. Dư nợ kỳ trước:</b>
                                        </th>
                                        <th>&nbsp;</th>

                                        <th class="text-right">
                                            <?php echo e(number_format($detail->prev_balance, 0, ',', '.') ?? ''); ?>

                                        </th>
                                    </tr>

                                </thead>
                                <tbody>

                                    <?php if(isset($detail->prev_receipt_detail) && count($detail->prev_receipt_detail) > 0): ?>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Số lượng dự kiến (thu trước theo dịch vụ)</th>
                                            <th>Số lượng sử dụng thực tế (đối soát theo dịch vụ)</th>
                                            <th>Đơn giá dịch vụ</th>
                                            <th>Số tiền dịch vụ trong tháng </th>
                                            <th>Tiền giảm trừ trong tháng </th>
                                            <th>Truy thu (+) / Hoàn trả (-) thực tế sau đối soát</th>
                                            <th>Số tiền cuối cùng phải thu sau giảm trừ & điều chỉnh</th>
                                        </tr>
                                        <?php $__currentLoopData = $detail->prev_receipt_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_prev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(date('d-m-Y', strtotime($item_prev->month))); ?></td>
                                                <td><?php echo e($item_prev->service->name ?? ''); ?></td>
                                                <td><?php echo e(number_format($item_prev->by_number, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item_prev->spent_number, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item_prev->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item_prev->amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item_prev->discount_amount, 0, ',', '.') ?? ''); ?>

                                                </td>
                                                <td><?php echo e(number_format($item_prev->adjustment_amount, 0, ',', '.') ?? ''); ?>

                                                </td>
                                                <td><?php echo e(number_format($item_prev->final_amount, 0, ',', '.') ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-scroll table-responsive">
                            <table id="dt_basic" class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-left"><b>2. Phí dự kiến</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Du kien thu thang nay -->

                                    <?php if(isset($detail->receipt_detail) && count($detail->receipt_detail) > 0): ?>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Đơn giá</th>
                                            <th>SL thực tế</span></th>
                                            <th>Giảm trừ</th>
                                            <th>Tạm tính</th>
                                            <th>Hoàn trả / phát sinh</th>
                                            <th>Tổng tiền</th>
                                        </tr>
                                        <?php $__currentLoopData = $detail->receipt_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(date('d-m-Y', strtotime($item->month))); ?></td>
                                                <td><?php echo e($item->service->name ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->spent_number, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->discount_amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->adjustment_amount, 0, ',', '.') ?? ''); ?>

                                                </td>
                                                <td><?php echo e(number_format($item->final_amount, 0, ',', '.') ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <form method="post" action="<?php echo e(route(Request::segment(2) . '.payment', $detail->id)); ?>"
                            onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($detail->id); ?>">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <tbody>
                                    <tr>
                                        <td><label><?php echo app('translator')->get('Mã hóa đơn'); ?></label></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_code); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><label><?php echo app('translator')->get('Tên hóa đơn'); ?></label></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_name); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><label><?php echo app('translator')->get('T/T thanh toán'); ?></label></td>
                                        <td class="text-right"><span
                                                class="label <?php echo e($detail->status == 'pending' ? 'label-warning' : 'label-success'); ?>"><?php echo e(__($detail->status)); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label> <strong> Tổng số tiền cần nộp </strong> </label>
                                        </td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_amount, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng giảm trừ</label></td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_discount, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng cộng các truy thu (+) / hoàn trả (-)</label></td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_adjustment, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng tiền thực tế sau đối soát tất cả dịch vụ</label></td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_final, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Đã thu</label></td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_paid, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Số tiền còn phải thu (+) hoặc thừa (-)</label></td>
                                        <td class="text-right">
                                            <b><?php echo e(number_format($detail->total_due, 0, ',', '.') ?? ''); ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Ngày bắt đầu kỳ thu</label></td>
                                        <td class="text-right">
                                            <?php echo e(date('d-m-Y', strtotime($detail->period_start))); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Ngày kết thúc kỳ thu</label></td>
                                        <td class="text-right">
                                            <?php echo e(date('d-m-Y', strtotime($detail->period_end))); ?>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if($detail->status == 'pendding'): ?>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> Xác nhận thanh
                                    toán</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-warning"> Đã thanh toán</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/show.blade.php ENDPATH**/ ?>