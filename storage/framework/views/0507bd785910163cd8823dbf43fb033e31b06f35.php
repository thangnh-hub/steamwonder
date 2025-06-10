<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .item_adjustment:last-child {
            position: absolute;
            top: -100vh;
        }

        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
        }

        .select2-container {
            width: 100% !important;
        }

        .tooltip-inner {
            white-space: nowrap;
            max-width: none;
            text-align: left
        }

        .box-flex-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-bordered>tbody>tr>td {
            vertical-align: middle
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
            <a class="btn btn-warning pull-right mr-10" target="_blank"
                href="<?php echo e(route(Request::segment(2) . '.print', $detail->id)); ?>" onclick="return openCenteredPopup(this.href)">
                <i class="fa fa-print"></i> <?php echo app('translator')->get('In TBP'); ?>
            </a>
        </h1>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>

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
                            <form method="post"
                                action="<?php echo e(route(Request::segment(2) . '.update_json_explanation', $detail->id)); ?>"
                                id="form_update_explanation">
                                <?php echo csrf_field(); ?>
                                <table class="table table-bordered table-hover no-footer no-padding">
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-left"><b>1. Số dư kỳ trước <span data-html="true"
                                                        data-toggle="tooltip"
                                                        title="
                                                        Hoàn trả sẽ nhập số nguyên dương (+)
                                                        <br>
                                                        Truy thu sẽ nhập số nguyên âm (-)">
                                                        <i class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                                </b>
                                            </th>
                                            <th class="text-right">
                                                <input type="number" name="prev_balance"
                                                    <?php echo e($detail->status == 'pending' ? '' : 'disabled'); ?> readonly
                                                    class="form-control pull-right prev_balance" style="max-width: 200px;"
                                                    placeholder="Nhập số dư kỳ trước" data-toggle="tooltip"
                                                    title="Tổng số dư kỳ trước của học sinh này, nếu có"
                                                    value="<?php echo e((int) $detail->prev_balance); ?>">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php if(isset($detail->prev_receipt_detail) && count($detail->prev_receipt_detail) > 0): ?>
                                            <tr>
                                                <th>Tháng</th>
                                                <th>Dịch vụ</th>
                                                <th>Đơn giá</th>
                                                <th>Số lượng </th>
                                                <th>Tạm tính </th>
                                                <th>Tiền giảm</th>
                                                <th>Truy thu (+) / Hoàn trả (-)</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                            <?php $__currentLoopData = $detail->prev_receipt_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_prev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($item_prev->month))); ?></td>
                                                    <td><?php echo e($item_prev->service->name ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->spent_number, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->amount, 0, ',', '.') ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->discount_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->adjustment_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->final_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tbody class="box_explanation">
                                        <?php if(isset($detail->student->receiptAdjustment)): ?>
                                            <?php $__currentLoopData = $detail->student->receiptAdjustment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($item->receipt_id == null || $item->receipt_id == $detail->id): ?>
                                                    <tr
                                                        class="item_adjustment <?php echo e(in_array($item->type, ['dunokytruoc', 'doisoat']) ? 'bg-gray' : ''); ?>">
                                                        <td class="text-center">
                                                            <?php if($detail->status == 'pending'): ?>
                                                                <?php if(in_array($item->type, ['dunokytruoc', 'doisoat'])): ?>
                                                                    <input type="checkbox" class="check_doisoat"
                                                                        onclick="updateBalance()"
                                                                        name="receipt_adjustment[]"
                                                                        value="<?php echo e($item->id); ?>"
                                                                        <?php echo e($item->receipt_id == $detail->id ? 'checked' : ''); ?>>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td colspan="4">
                                                            <?php if(in_array($item->type, ['dunokytruoc', 'doisoat'])): ?>
                                                                <?php echo e($item->note); ?>

                                                            <?php else: ?>
                                                                <input type="text"
                                                                    <?php echo e($detail->status == 'pending' ? '' : 'disabled'); ?>

                                                                    name="adjustment[<?php echo e($item->id); ?>][note]"
                                                                    class="form-control action_change"
                                                                    value="<?php echo e($item->note); ?>"
                                                                    placeholder="Nội dung Truy thu/Hoàn trả">
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(in_array($item->type, ['dunokytruoc', 'doisoat'])): ?>
                                                                <input type="hidden" name="list_doisoat[]"
                                                                    value="<?php echo e($item->id); ?>">
                                                                <span
                                                                    class="final_amount"><?php echo e((int) $item->final_amount); ?></span>
                                                            <?php else: ?>
                                                                <input type="number"
                                                                    <?php echo e($detail->status == 'pending' ? '' : 'disabled'); ?>

                                                                    name="adjustment[<?php echo e($item->id); ?>][final_amount]"
                                                                    class="form-control action_change"
                                                                    value="<?php echo e((int) $item->final_amount); ?>"
                                                                    placeholder="Giá trị tương ứng">
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if(in_array($item->type, ['dunokytruoc', 'doisoat'])): ?>
                                                                <?php echo e(__($item->type)); ?>

                                                            <?php else: ?>
                                                                <select name="adjustment[<?php echo e($item->id); ?>][type]"
                                                                    class="form-control">
                                                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(!in_array($key, ['dunokytruoc', 'doisoat'])): ?>
                                                                            <option value="<?php echo e($key); ?>">
                                                                                <?php echo e(__($val)); ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($detail->status == 'pending'): ?>
                                                                <?php if(!in_array($item->type, ['dunokytruoc', 'doisoat'])): ?>
                                                                    <button
                                                                        class="btn btn-sm btn-success btn_save_adjustment"
                                                                        type="button" data-toggle="tooltip" onclick=""
                                                                        title="<?php echo app('translator')->get('Save'); ?>">
                                                                        <i class="fa fa-save"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-danger" type="button"
                                                                        data-toggle="tooltip"
                                                                        onclick="$(this).closest('tr').remove();updateBalance()"
                                                                        title="<?php echo app('translator')->get('Delete'); ?>">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        
                                        <tr class="item_adjustment">
                                            <td class="text-center"></td>
                                            <td colspan="4">
                                                <input type="text" name="adjustment[0][note]"
                                                    class="form-control action_change"
                                                    placeholder="Nội dung Truy thu/Hoàn trả">
                                            </td>
                                            <td>
                                                <input type="number" name="adjustment[0][final_amount]"
                                                    class="form-control action_change" placeholder="Giá trị tương ứng">
                                            </td>
                                            <td>
                                                <select name="adjustment[0][type]" class="form-control">
                                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!in_array($key, ['dunokytruoc', 'doisoat'])): ?>
                                                            <option value="<?php echo e($key); ?>">
                                                                <?php echo e(__($val)); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success btn_save_adjustment" type="button"
                                                    data-toggle="tooltip" onclick="" title="<?php echo app('translator')->get('Save'); ?>">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="$(this).closest('tr').remove();updateBalance()"
                                                    title="<?php echo app('translator')->get('Delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <?php if($detail->status == 'pending'): ?>
                                <button class="btn btn-warning btn_explanation mt-10"><?php echo app('translator')->get('Thêm giải trình'); ?></button>
                            <?php endif; ?>
                        </div>
                        <div class="custom-scroll table-responsive mt-15">
                            <?php if(isset($detail->receiptDetail) && count($detail->receiptDetail) > 0): ?>
                                <table class="table table-bordered table-hover no-footer no-padding">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-left"><b>2. Phí dự kiến</b></th>
                                            <th colspan="3"class="text-right">
                                                <?php if($detail->status == 'pending'): ?>
                                                    <button data-toggle="modal" data-target="#modal_show_service"
                                                        class="btn btn-warning"><?php echo app('translator')->get('Thay đổi kỳ tính phí cho HS'); ?></button>
                                                <?php endif; ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Du kien thu thang nay -->
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</span></th>
                                            <th>Tạm tính</th>
                                            <th>Giảm trừ</th>
                                            <th>Tổng tiền</th>
                                            <th>Ghi chú</th>
                                            <th></th>

                                        </tr>
                                        <?php $__currentLoopData = $detail->receiptDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(date('m-Y', strtotime($item->month))); ?></td>
                                                <td><?php echo e($item->services_receipt->name ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->by_number, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->discount_amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->final_amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo $item->note ?? ''; ?></td>
                                                <td>
                                                    <?php if($detail->status == 'pending'): ?>
                                                        <button
                                                            class="btn btn-sm btn-danger delete_receipt_detail_and_recalculate"
                                                            data-receipt="<?php echo e($detail->id); ?>"
                                                            data-id = "<?php echo e($item->id); ?>" type="button"
                                                            data-toggle="tooltip" title="<?php echo app('translator')->get('Xóa'); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <form method="post" action="<?php echo e(route(Request::segment(2) . '.payment', $detail->id)); ?>"
                            onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($detail->id); ?>">
                            <table class="table table-bordered table-hover no-footer no-padding table_paid">
                                <tbody>
                                    <tr>
                                        <td><?php echo app('translator')->get('Mã TBP'); ?></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_code); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Tên TBP'); ?></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_name); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Trạng thái thanh toán'); ?></td>
                                        <td class="text-right"><span
                                                class="label <?php echo e($detail->status == 'pending' ? 'label-warning' : 'label-success'); ?>"><?php echo e(__($detail->status)); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php echo app('translator')->get('Tổng tiền'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_amount, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Tổng giảm trừ'); ?></td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_discount, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Số dư kỳ trước'); ?></td>
                                        <td class="text-right total_prev_balance"
                                            data-balance="<?php echo e($detail->prev_balance); ?>">
                                            <?php echo e(number_format($detail->prev_balance, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td><?php echo app('translator')->get('Tổng tiền thực tế sau đối soát tất cả dịch vụ'); ?> <span data-html="true" data-toggle="tooltip"
                                                title="= [Tổng tiền] - [Giảm trừ] - [Số dư kỳ trước]">
                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                        </td>
                                        <td class="text-right total_final" data-final="<?php echo e($detail->total_final); ?>">
                                            <?php echo e(number_format($detail->total_final, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="box-flex-between">
                                                <span><?php echo app('translator')->get('Đã thu'); ?></span>
                                                <?php if($detail->status != 'pending'): ?>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-toggle="modal" data-target="#modal_receipt_transaction">Chi
                                                        tiết</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_paid, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td> <?php echo app('translator')->get('Số tiền còn phải thu (+) hoặc thừa (-)'); ?>
                                            <span data-toggle="tooltip"
                                                title="Số tiền thừa sẽ được chuyển qua kỳ thanh toán tiếp theo"><i
                                                    class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                        </td>
                                        <td class="text-right total_due" data-due="<?php echo e($detail->total_due); ?>">
                                            <?php echo e(number_format($detail->total_due, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Hạn thanh toán'); ?></td>
                                        <td class="text-right">
                                            <input type="date" name="due_date" class="form-control"
                                                <?php echo e($detail->status == 'approved' ? '' : 'disabled'); ?>

                                                value="<?php echo e($detail->json_params->due_date ?? $due_date); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if($detail->status == 'pending'): ?>
                                <button type="button" class="btn btn-success btn_approved">
                                    <?php echo app('translator')->get('Duyệt TBP'); ?>
                                </button>
                            <?php endif; ?>
                            <?php if($detail->status == 'approved'): ?>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> <?php echo app('translator')->get('Xác nhận đã thanh toán'); ?>
                                </button>
                            <?php endif; ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="modal fade" id="modal_show_service" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thay đổi kỳ tính phí cho học sinh'); ?></h3>
                        </h3>
                    </div>
                    <form action="<?php echo e(route('receipt.update_student_service_and_fee')); ?>" method="POST"
                        class="form_detail_service">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="receipt_id" value="<?php echo e($detail->id); ?>">
                        <input type="hidden" name="student_id" value="<?php echo e($detail->student->id); ?>">
                        <div class="modal-body show_detail_service">
                            <div class="modal-alert"></div>
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Hệ đào tạo'); ?></th>
                                        <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                        <th><?php echo app('translator')->get('Chu kỳ thu'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="box_service">
                                    <?php if(isset($detail->student->studentServices) && count($detail->student->studentServices) > 0): ?>
                                        <?php $__currentLoopData = $detail->student->studentServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($item->services->name ?? ''); ?></td>
                                                <td><?php echo e($item->services->service_category->name ?? ''); ?></td>
                                                <td><?php echo e($item->services->education_program->name ?? ''); ?></td>
                                                <td><?php echo e(__($item->services->service_type ?? '')); ?></td>
                                                <td>
                                                    <?php if(isset($item->services->serviceDetail) && $item->services->serviceDetail->count() > 0): ?>
                                                        <?php $__currentLoopData = $item->services->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <ul>
                                                                <li><?php echo app('translator')->get('Số tiền'); ?>:
                                                                    <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?>

                                                                </li>
                                                                <li><?php echo app('translator')->get('Số lượng'); ?>:
                                                                    <?php echo e($detail_service->quantity ?? ''); ?>

                                                                </li>
                                                                <li><?php echo app('translator')->get('Từ'); ?>:
                                                                    <?php echo e(isset($detail_service->start_at) ? \Carbon\Carbon::parse($detail_service->start_at)->format('d-m-Y') : ''); ?>

                                                                </li>
                                                                <li><?php echo app('translator')->get('Đến'); ?>:
                                                                    <?php echo e(isset($detail_service->end_at) ? \Carbon\Carbon::parse($detail_service->end_at)->format('d-m-Y') : ''); ?>

                                                                </li>
                                                            </ul>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <select class="form-control select2 w-100"
                                                        name="student_services[<?php echo e($item->id); ?>][payment_cycle_id]">
                                                        <?php if(isset($payment_cycle) && count($payment_cycle) > 0): ?>
                                                            <?php $__currentLoopData = $payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val->id); ?>"
                                                                    <?php echo e($item->payment_cycle_id == $val->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($val->name ?? ''); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Lưu và chạy lại phí'); ?>
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_receipt_transaction" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Danh sách khoản thu của TBP'); ?></h3>
                        </h3>
                    </div>
                    <form action="<?php echo e(route('receipt.crud_receipt_transaction')); ?>" method="POST"
                        id="form_receipt_transaction">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="receipt_id" value="<?php echo e($detail->id); ?>">
                        <input type="hidden" name="type" value="create">
                        <div class="modal-body show_receipt_transaction">
                            <div class="modal-alert"></div>
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                        <th><?php echo app('translator')->get('Số tiền thanh toán'); ?></th>
                                        <th><?php echo app('translator')->get('Ngày thanh toán'); ?></th>
                                        <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                        <th><?php echo app('translator')->get('Thu ngân'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="box_service">
                                    <?php if(isset($detail->receiptTransaction) && count($detail->receiptTransaction) > 0): ?>
                                        <?php $__currentLoopData = $detail->receiptTransaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($loop->index + 1); ?></td>
                                                <td><?php echo e(number_format($item->paid_amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(date('d-m-Y', strtotime($item->payment_date))); ?></td>
                                                <td><?php echo e($item->json_params->note ?? ''); ?></td>
                                                <td><?php echo e($item->user_cashier->name ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center"><?php echo app('translator')->get('Chưa có giao dịch nào'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if($detail->status == 'approved'): ?>
                                <div class="row">
                                    <h4 class="text-center form-group col-md-12"><?php echo app('translator')->get('Thông tin thanh toán cho kỳ này'); ?></h4>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Nhập số tiền thanh toán'); ?> <small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="paid_amount"
                                                placeholder="<?php echo app('translator')->get('Nhập số tiền thanh toán'); ?>" value="<?php echo e(old('paid_amount')); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Ngày thanh toán'); ?> <small class="text-red">*</small></label>
                                            <input type="date" class="form-control" name="payment_date"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Ghi chú'); ?></label>
                                            <textarea name="json_params[note]" class="form-control" cols="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>
                        <div class="modal-footer">
                            <?php if($detail->status == 'approved'): ?>
                                <button type="submit" class="btn btn-success btn_save_transaction">
                                    <i class="fa fa-save"></i> <?php echo app('translator')->get('Lưu lại'); ?>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> <?php echo app('translator')->get('Đóng'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        // Thêm giải trình html
        var adjustmentTypes = <?php echo json_encode($type, 15, 512) ?>;
        $('.btn_explanation').click(function() {
            var lastElement = $('.item_adjustment').last();
            var clonedElement = lastElement.clone();
            // Lấy giá trị `key` cuối cùng từ dòng cuối hoặc gán giá trị thời gian thực
            var currentDateTime = Math.floor(Date.now() / 1000);
            // Điều chỉnh tất cả các input và select trong dòng clone
            clonedElement.find('input, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    // Thay thế key cũ bằng key mới
                    var updatedName = name.replace(/\[\d+\]/, `[${currentDateTime}]`);
                    $(this).attr('name', updatedName); // Cập nhật name
                }
                if ($(this).is('input')) {
                    $(this).val(''); // Reset giá trị input
                } else if ($(this).is('select')) {
                    $(this).prop('selectedIndex', 0); // Chọn option đầu tiên
                }
            });
            // Thêm dòng clone vào bảng
            lastElement.before(clonedElement);
            return;
        })

        // Thay đổi giá trị prev_balance khi các cập nhật giải trình
        $(document).on('click', '.btn_save_adjustment', function() {
            updateBalance();
        })

        function updateBalance() {
            var total = 0;
            $('input.action_change[type="number"]').each(function() {
                var value = parseFloat($(this).val()) ||
                    0; // Chuyển giá trị thành số, mặc định 0 nếu không hợp lệ
                total += value;
            });
            $('.final_amount').each(function() {
                var value = parseFloat($(this).html()) || 0; // Chuyển giá trị thành số, mặc định 0 nếu không hợp lệ
                if ($(this).parents('tr').find('.check_doisoat').is(':checked') == true) {
                    total += value;
                }
            });
            $('.prev_balance').val(total).change();
        }

        //Thay đổi số tiền tổng
        $(document).on('change', '.prev_balance', function() {
            var _balance = parseInt($(this).val(), 10);
            if (isNaN(_balance)) {
                _balance = 0;
            }
            $('.total_prev_balance').html(new Intl.NumberFormat('vi-VN').format(_balance));
            updateJsonExplanation();
        })

        // Hàm cập nhật giải trình lưu lại trong JSON và tính lại số tiền
        function updateJsonExplanation() {
            var _url = $('#form_update_explanation').prop('action')
            var formData = $('#form_update_explanation').serialize();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    hide_loading_notification();
                    if (response.data == 'warning') {
                        location.reload();
                    }
                    $('.total_final').html(new Intl.NumberFormat('vi-VN').format(response.data.total_final));
                    $('.total_due').html(new Intl.NumberFormat('vi-VN').format(response.data.total_due));
                },
                error: function(data) {
                    hide_loading_notification();
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        }



        // Cập nhật giải trình khi form được submit
        // $('#form_update_explanation').on('submit', function(event) {
        //     event.preventDefault();
        //     updateJsonExplanation();
        // });


        // Xử lý sự kiện click nút duyệt TBP
        $('.btn_approved').click(function() {
            if (confirm('<?php echo e(__('confirm_action')); ?>')) {
                var _url = "<?php echo e(route(Request::segment(2) . '.approved', $detail->id)); ?>";
                var formData = $('#form_update_explanation').serialize();
                show_loading_notification();
                $.ajax({
                    type: "POST",
                    url: _url,
                    data: formData,
                    success: function(response) {
                        if (response) {
                            hide_loading_notification();
                            window.location.reload();
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert-danger").fadeOut(3000, function() {});
                            }, 800);
                            hide_loading_notification();
                        }
                    },
                    error: function(data) {
                        hide_loading_notification();
                        var errors = data.responseJSON.message;
                        alert(data);
                    }
                });
            }

        });

        $(document).on('click', '.update_student_service', function() {
            var _id = $(this).data('id');
            var _payment_cycle_id = $(this).closest('tr').find('.payment_cycle').val();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: "<?php echo e(route('student.updateService.ajax')); ?>",
                data: {
                    id: _id,
                    payment_cycle_id: _payment_cycle_id,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    hide_loading_notification();
                    if (response.message === 'success') {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Cập nhật thành công!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function() {
                    hide_loading_notification();
                    alert("Lỗi cập nhật.");
                }
            });
        });

        // Thay đổi kỳ thanh toán
        $('#form_receipt_transaction').on('submit', function(event) {
            event.preventDefault();
            var _url = $(this).prop('action')
            var formData = $(this).serialize();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    if (response) {
                        hide_loading_notification();
                        var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                        if (response.data == 'success') {
                            location.reload();
                        }

                    } else {
                        hide_loading_notification();
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function(data) {
                    hide_loading_notification();
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        });

        $('.delete_receipt_detail_and_recalculate').click(function() {
            if (confirm('<?php echo e(__('confirm_action')); ?>')) {
                var receipt_id = $(this).data('receipt');
                var detail_id = $(this).data('id');
                show_loading_notification();
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route('receipt.deletePaymentDetailsAndRecalculate')); ?>",
                    data: {
                        receipt_id: receipt_id,
                        detail_id: detail_id,
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        hide_loading_notification();
                        if (response) {
                            hide_loading_notification();
                            var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                            $('.box_alert').prepend(_html);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                            if (response.data == 'success') {
                                location.reload();
                            }

                        } else {
                            hide_loading_notification();
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                    },
                    error: function(data) {
                        hide_loading_notification();
                        var errors = data.responseJSON.message;
                        alert(data);
                    }
                });
            }
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/show.blade.php ENDPATH**/ ?>