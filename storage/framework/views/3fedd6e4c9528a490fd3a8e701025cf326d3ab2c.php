<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .modal-header {
            background-color: #3c8dbc;
            color: white;
        }

        .table-wrapper {
            max-height: 450px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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


        <div class="box box-default">
            <div class="box-body ">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin chính </h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_2" data-toggle="tab">
                                                <h5>Người thân của bé</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_3" data-toggle="tab">
                                                <h5>Dịch vụ đăng ký</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_4" data-toggle="tab">
                                                <h5>Biên lai thu phí</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_5" data-toggle="tab">
                                                <h5>Chương trình KH.Mãi</h5>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- TAB 1: Thông tin học sinh -->
                                        <div class="tab-pane active" id="tab_1">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Mã học sinh'); ?>:</strong>
                                                        <?php echo e($detail->student_code ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Họ và tên'); ?>:</strong>
                                                        <?php echo e($detail->first_name ?? ''); ?> <?php echo e($detail->last_name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Ngày sinh'); ?>:</strong>
                                                        <?php echo e($detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Tên thường gọi'); ?>:</strong>
                                                        <?php echo e($detail->nickname ?? ''); ?>

                                                    </p>
                                                </div>

                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Khu vực'); ?>:</strong>
                                                        <?php echo e($detail->area->name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Giới tính'); ?>:</strong>
                                                        <?php echo e(__($detail->sex ?? '')); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Lớp đang học'); ?>:</strong>
                                                        <?php echo e($detail->currentClass->name ?? ''); ?>

                                                    </p>
                                                    <p><strong><?php echo app('translator')->get('Ngày nhập học'); ?>:</strong>
                                                        <?php echo e(isset($detail->enrolled_at) && $detail->enrolled_at != '' ? date('d-m-Y', strtotime($detail->enrolled_at)) : ''); ?>

                                                    </p>
                                                </div>

                                                <div class="col-md-4">
                                                    <p><strong><?php echo app('translator')->get('Ảnh đại diện'); ?>:</strong>
                                                    </p>
                                                    <a target="_blank"
                                                        href="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>">
                                                        <img src="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>"
                                                            alt="avatar" style="max-height:180px;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAB 2: Người thân -->
                                        <div class="tab-pane" id="tab_2">
                                            <?php if($detail->studentParents->isNotEmpty()): ?>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                            <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                                            <th><?php echo app('translator')->get('Mối quan hệ'); ?></th>
                                                            <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày sinh'); ?></th>
                                                            <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                                            <th><?php echo app('translator')->get('Email'); ?></th>
                                                            <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $detail->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($index + 1); ?></td>
                                                                <td>
                                                                    <?php if(!empty($relation->parent->avatar)): ?>
                                                                        <img src="<?php echo e(asset($relation->parent->avatar)); ?>"
                                                                            alt="Avatar" width="100" height="100"
                                                                            style="object-fit: cover;">
                                                                    <?php else: ?>
                                                                        <span class="text-muted">No image</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo e($relation->parent->first_name ?? ''); ?>

                                                                    <?php echo e($relation->parent->last_name ?? ''); ?> </td>
                                                                <td><?php echo e($relation->relationship->title ?? ''); ?></td>
                                                                <td><?php echo e(__($relation->parent->sex ?? '')); ?></td>

                                                                <td>
                                                                    <?php echo e($relation->parent->birthday ? \Carbon\Carbon::parse($relation->parent->birthday)->format('d/m/Y') : ''); ?>

                                                                </td>

                                                                <td><?php echo e($relation->parent->phone ?? ''); ?></td>
                                                                <td><?php echo e($relation->parent->email ?? ''); ?></td>
                                                                <td><?php echo e($relation->parent->address ?? ''); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p class="text-muted"><?php echo app('translator')->get('Không có người thân nào được liên kết.'); ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- TAB 3: Dịch vụ đăng ký -->
                                        <div class="tab-pane" id="tab_3">
                                            <div class="box-body ">
                                                <h4 class="mt-4 ">Danh sách dịch vụ đăng ký</h4>
                                                <br>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Hệ đào tạo'); ?></th>
                                                            <th><?php echo app('translator')->get('Độ tuổi'); ?></th>
                                                            <th><?php echo app('translator')->get('Tính chất dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                                            <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Chu kỳ thu'); ?></th>
                                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $activeServices = $detail->studentServices->where(
                                                                'status',
                                                                'active',
                                                            );
                                                        ?>
                                                        <?php if($activeServices->count()): ?>
                                                            <?php $__currentLoopData = $activeServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($loop->index + 1); ?></td>
                                                                    <td><?php echo e($row->services->name ?? ''); ?></td>
                                                                    <td><?php echo e($row->services->service_category->name ?? ''); ?>

                                                                    </td>
                                                                    <td><?php echo e($row->services->education_program->name ?? ''); ?>

                                                                    </td>
                                                                    <td><?php echo e($row->services->education_age->name ?? ''); ?>

                                                                    </td>
                                                                    <td><?php echo e($row->services->is_attendance == 0 ? 'Không theo điểm danh' : 'Tính theo điểm danh'); ?>

                                                                    </td>
                                                                    <td><?php echo e(__($row->services->service_type ?? '')); ?></td>

                                                                    <td>
                                                                        <?php if(isset($row->services->serviceDetail) && $row->services->serviceDetail->count() > 0): ?>
                                                                            <?php $__currentLoopData = $row->services->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <ul>
                                                                                    <li>Số tiền:
                                                                                        <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?>

                                                                                    </li>
                                                                                    <li>Số lượng:
                                                                                        <?php echo e($detail_service->quantity ?? ''); ?>

                                                                                    </li>
                                                                                    <li>Từ:
                                                                                        <?php echo e(isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : ''); ?>

                                                                                    </li>
                                                                                    <li>Đến:
                                                                                        <?php echo e(isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : ''); ?>

                                                                                    </li>
                                                                                </ul>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($row->paymentcycle->name ?? ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($row->json_params->note ?? ''); ?>

                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>

                                                </table>
                                                <br>
                                                <?php
                                                    $cancelledServices = $detail->studentServices->where(
                                                        'status',
                                                        'cancelled',
                                                    );
                                                ?>
                                                <?php if($cancelledServices->count()): ?>
                                                    <h4 class="mt-4 ">Danh sách dịch vụ bị huỷ</h4>
                                                    <br>
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo app('translator')->get('STT'); ?></th>
                                                                <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                                                <th><?php echo app('translator')->get('Ngày bắt đầu'); ?></th>
                                                                <th><?php echo app('translator')->get('Ngày kết thúc'); ?></th>
                                                                <th><?php echo app('translator')->get('Người cập nhật'); ?></th>
                                                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__currentLoopData = $cancelledServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($loop->index + 1); ?></td>
                                                                    <td><?php echo e($row->services->name ?? ''); ?></td>
                                                                    <td>
                                                                        <?php echo e($row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($row->cancelled_at ? \Carbon\Carbon::parse($row->cancelled_at)->format('d-m-Y') : ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($row->adminUpdated->name ?? ''); ?>

                                                                        (<?php echo e($row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('H:i:s d-m-Y') : ''); ?>)
                                                                    </td>
                                                                    <td><span class="badge badge-danger">Đã huỷ</span></td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- TAB 4: Biên lai thu phí -->
                                        <div class="tab-pane " id="tab_4">
                                            <div class="box-body ">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Mã biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên biểu phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Số dư kỳ trước '); ?></th>
                                                            <th><?php echo app('translator')->get('Thành tiền'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng giảm trừ'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng tiền truy thu/hoàn trả'); ?></th>
                                                            <th><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                                            <th><?php echo app('translator')->get('Đã thanh toán'); ?></th>
                                                            <th><?php echo app('translator')->get('Còn lại'); ?></th>
                                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày tạo phí'); ?></th>
                                                            <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            function format_currency($price)
                                                            {
                                                                return isset($price) && is_numeric($price)
                                                                    ? number_format($price, 0, ',', '.') . ' đ'
                                                                    : '';
                                                            }
                                                        ?>
                                                        <?php if($detail->studentReceipt->count()): ?>
                                                            <?php $__currentLoopData = $detail->studentReceipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($loop->index + 1); ?></td>
                                                                    <td><?php echo e($row->receipt_code ?? ''); ?></td>
                                                                    <td><?php echo e($row->receipt_name ?? ''); ?></td>

                                                                    <td><?php echo e(format_currency($row->prev_balance)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_amount)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_discount)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_adjustment)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_final)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_paid)); ?></td>
                                                                    <td><?php echo e(format_currency($row->total_due)); ?></td>
                                                                    <td><?php echo e(__($row->status)); ?></td>
                                                                    <td><?php echo e($row->note ?? ''); ?></td>
                                                                    <td><?php echo e(isset($row->receipt_date) ? \Illuminate\Support\Carbon::parse($row->receipt_date)->format('d-m-Y') : ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary btn_show_detail"
                                                                            data-toggle="tooltip"
                                                                            data-id="<?php echo e($row->id); ?>"
                                                                            data-url="<?php echo e(route('receipt.view', $row->id)); ?>"
                                                                            title="<?php echo app('translator')->get('Show'); ?>"
                                                                            data-original-title="<?php echo app('translator')->get('Show'); ?>">
                                                                            <i class="fa fa-eye"></i> Xem
                                                                        </button>

                                                                        <a href="<?php echo e(route('receipt.show', $row->id)); ?>">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-warning  mr-10"
                                                                                title="<?php echo app('translator')->get('Cập nhật'); ?>"
                                                                                data-original-title="<?php echo app('translator')->get('Cập nhật'); ?>">
                                                                                <i class="fa fa-money"></i> Cập nhật
                                                                            </button>
                                                                        </a>

                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        
                                        <div class="tab-pane " id="tab_5">
                                            <div class="box-body ">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('Mã CT Kh.Mãi'); ?></th>
                                                            <th><?php echo app('translator')->get('Tên CT Kh.Mãi'); ?></th>
                                                            <th><?php echo app('translator')->get('Mô tả'); ?></th>
                                                            <th><?php echo app('translator')->get('Loại'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày bắt đầu được hưởng Kh.Mãi'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày kết thúc được hưởng Kh.Mãi'); ?></th>
                                                            <th><?php echo app('translator')->get('Chi tiết Kh.Mãi'); ?></th>
                                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $detail->studentPromotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($row->promotion->promotion_code ?? ''); ?></td>
                                                                <td><?php echo e($row->promotion->promotion_name ?? ''); ?></td>
                                                                <td><?php echo e($row->promotion->description ?? ''); ?></td>
                                                                <td><?php echo e(__($row->promotion->promotion_type)); ?></td>
                                                                <td>
                                                                    <?php echo e(\Carbon\Carbon::parse($row->time_start)->format('Y-m-d') ?? ''); ?>


                                                                </td>
                                                                <td>
                                                                    <?php echo e(\Carbon\Carbon::parse($row->time_end)->format('Y-m-d') ?? ''); ?>

                                                                </td>
                                                                <td>
                                                                    <?php if(isset($row->promotion->json_params->is_payment_cycle) && $row->promotion->json_params->is_payment_cycle == 1): ?>
                                                                        <?php $__currentLoopData = $row->promotion->json_params->payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key_cycle => $item_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $payment_cycle = $list_payment_cycle->firstWhere('id',(int)$key_cycle);
                                                                            ?>
                                                                            <div class="box-title">
                                                                                <?php echo e($payment_cycle->name ?? ''); ?></div>
                                                                            <?php $__currentLoopData = $item_cycle->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    $service_detail = $services->firstWhere('id',$val->service_id);
                                                                                ?>
                                                                                <ul>
                                                                                    <li>Dịch vụ:
                                                                                        <?php echo e($service_detail->name ?? ''); ?>

                                                                                    </li>
                                                                                    <li>Giá trị áp dụng:
                                                                                        <?php echo e(number_format($val->value, 0, ',', '.')); ?>

                                                                                    </li>
                                                                                    <li>Số lần áp dụng:
                                                                                        <?php echo e($val->apply_count ?? ''); ?>

                                                                                    </li>
                                                                                </ul>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php else: ?>
                                                                        <?php $__currentLoopData = $row->promotion->json_params->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                                $service_detail = $services->firstWhere('id',$val->service_id);
                                                                            ?>
                                                                        <ul>
                                                                            <li>Dịch vụ:
                                                                                <?php echo e($service_detail->name ?? ''); ?>

                                                                            </li>
                                                                            <li>Giá trị áp dụng:
                                                                                <?php echo e(number_format($val->value, 0, ',', '.')); ?>

                                                                            </li>
                                                                            <li>Số lần áp dụng:
                                                                                <?php echo e($val->apply_count ?? ''); ?>

                                                                            </li>
                                                                        </ul>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo e(__($row->status)); ?>

                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer ">
                <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_show_deduction" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thông tin hóa đơn'); ?></h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_deduction">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.btn_show_detail').click(function(e) {
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    if (response) {
                        $('.show_detail_deduction').html(response.data.view);
                        $('#modal_show_deduction').modal('show');
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
                            $('.alert').remove();
                        }, 3000);
                    }

                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/students/detail.blade.php ENDPATH**/ ?>