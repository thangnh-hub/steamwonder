<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .flex-inline-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

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

        td ul {
            margin-block-start: 0px !important;
            padding-inline-start: 10px !important;
        }

        input[type="radio"] {
            transform: scale(1.5);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Thêm mới học viên'); ?></a>
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
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin học sinh <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Người thân của bé</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>Dịch vụ đã đăng ký</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_4" data-toggle="tab">
                                            <h5>Quản lý TBP</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_5" data-toggle="tab">
                                            <h5>CT Kh.Mãi được áp dụng</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="box-body">
                                            <div class="d-flex-wap">
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Khu vực'); ?><small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value=""><?php echo app('translator')->get('Chọn khu vực'); ?></option>
                                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val->id); ?>"
                                                                    <?php echo e(old('area_id', $detail->area_id) == $val->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($val->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Họ'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name"
                                                            value="<?php echo e(old('first_name', $detail->first_name)); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name"
                                                            value="<?php echo e(old('last_name', $detail->last_name)); ?>" required>
                                                    </div>
                                                </div>



                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên thường gọi'); ?></label>
                                                        <input type="text" class="form-control" name="nickname"
                                                            value="<?php echo e(old('nickname', $detail->nickname)); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Giới tính'); ?></label>
                                                        <select name="sex" class="form-control select2">
                                                            <?php $__currentLoopData = $list_sex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>"
                                                                    <?php echo e(old('sex', $detail->sex) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày sinh'); ?></label>
                                                        <input type="date" class="form-control" name="birthday"
                                                            value="<?php echo e(old('birthday', $detail->birthday)); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày nhập học'); ?></label>
                                                        <input type="date" class="form-control" name="enrolled_at"
                                                            value="<?php echo e(old('enrolled_at', $detail->enrolled_at)); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                        <select name="status" class="form-control select2">
                                                            <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>"
                                                                    <?php echo e(old('status', $detail->status) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Chính sách được hưởng'); ?></label>
                                                        <select name="policies[]" class="form-control select2" multiple>
                                                            <?php
                                                                $selectedPolicies = $detail->studentPolicies
                                                                    ->pluck('policy_id')
                                                                    ->toArray();
                                                            ?>
                                                            <?php $__currentLoopData = $list_policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($policy->id); ?>"
                                                                    <?php echo e(in_array($policy->id, $detail->studentPolicies->pluck('policy_id')->toArray()) ? 'selected' : ''); ?>>
                                                                    <?php echo e($policy->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Chu kỳ thu dịch vụ'); ?></label>
                                                        <select style="width:100%" name="payment_cycle_id"
                                                            class="form-control select2">
                                                            <option value="">Chọn</option>
                                                            <?php $__currentLoopData = $list_payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option
                                                                    <?php echo e(old('payment_cycle_id', $detail->payment_cycle_id) == $payment_cycle->id ? 'selected' : ''); ?>

                                                                    value="<?php echo e($payment_cycle->id); ?>">
                                                                    <?php echo e($payment_cycle->name ?? ''); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group box_img_right">
                                                        <label><?php echo app('translator')->get('Ảnh đại diện'); ?></label>
                                                        <div id="image-holder">
                                                            <img src="<?php echo e(!empty($detail->avatar) ? asset($detail->avatar) : url('themes/admin/img/no_image.jpg')); ?>"
                                                                style="max-height: 120px;">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder"
                                                                    class="btn btn-primary lfm" data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden"
                                                                type="hidden" name="avatar"
                                                                placeholder="<?php echo app('translator')->get('Image source'); ?>"
                                                                value="<?php echo e(old('avatar', $detail->avatar)); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="tab-pane " id="tab_2">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#addParentModal">
                                                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('Cập nhật người thân'); ?>
                                                </button>
                                            </div>

                                            <br>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                                            <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                            <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                                            <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày sinh'); ?></th>
                                                            <th><?php echo app('translator')->get('Số CMND/CCCD'); ?></th>
                                                            <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                                            <th><?php echo app('translator')->get('Email'); ?></th>
                                                            <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                                            <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                            <th><?php echo app('translator')->get('Quan hệ'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($detail->studentParents->count()): ?>
                                                            <?php $__currentLoopData = $detail->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr class="valign-middle">
                                                                    <td>
                                                                        <?php echo e($loop->iteration); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php if(!empty($row->parent->avatar)): ?>
                                                                            <img src="<?php echo e(asset($row->parent->avatar)); ?>"
                                                                                alt="Avatar" width="100"
                                                                                height="100" style="object-fit: cover;">
                                                                        <?php else: ?>
                                                                            <span class="text-muted">No image</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <a target="_blank"
                                                                            href="<?php echo e(route('parents.show', $row->parent->id)); ?>">
                                                                            <?php echo e($row->parent->first_name ?? ''); ?>

                                                                            <?php echo e($row->parent->last_name ?? ''); ?>

                                                                        </a>
                                                                    </td>
                                                                    <td><?php echo app('translator')->get($row->parent->sex ?? ''); ?></td>
                                                                    <td><?php echo e($row->parent->birthday ? \Carbon\Carbon::parse($row->parent->birthday)->format('d/m/Y') : ''); ?>

                                                                    </td>
                                                                    <td><?php echo e($row->parent->identity_card ?? ''); ?></td>
                                                                    <td><?php echo e($row->parent->phone ?? ''); ?></td>
                                                                    <td><?php echo e($row->parent->email ?? ''); ?></td>
                                                                    <td><?php echo e($row->parent->address ?? ''); ?></td>
                                                                    <td><?php echo e($row->parent->area->name ?? ''); ?></td>
                                                                    <td><?php echo app('translator')->get($row->parent->status ?? ''); ?></td>
                                                                    <td><?php echo e($row->relationship->title ?? ''); ?></td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane " id="tab_3">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#addServiceModal">
                                                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('Đăng ký dịch vụ'); ?>
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#reincarnationModal">
                                                    <i class="fa fa-recycle"></i> <?php echo app('translator')->get('Xử lý tái tục dịch vụ'); ?>
                                                </button>
                                            </div>
                                            <br>
                                            <div class="table-responsive">
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
                                                            <th><?php echo app('translator')->get('Ngày bắt đầu'); ?></th>
                                                            <th><?php echo app('translator')->get('Ngày kết thúc'); ?></th>
                                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                                            <th><?php echo app('translator')->get('Chức năng'); ?></th>
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
                                                                        <?php echo e($row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($row->cancelled_at ? \Carbon\Carbon::parse($row->cancelled_at)->format('d-m-Y') : ''); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($row->json_params->note ?? ''); ?>

                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger delete_student_service"
                                                                            data-id="<?php echo e($row->id); ?>">
                                                                            <i class="fa fa-close"></i> Hủy
                                                                        </button>
                                                                        <button data-id="<?php echo e($row->id); ?>"
                                                                            type="button"
                                                                            class="btn btn-primary btn-sm update_student_service"
                                                                            data-toggle="modal"
                                                                            data-target="#editServiceModal">
                                                                            <i class="fa fa-pencil"></i> <?php echo app('translator')->get('Cập nhật'); ?>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
                                                <div class="table-responsive">
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
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- TAB 4: Biên lai thu phí -->
                                    <div class="tab-pane" id="tab_4">
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
                                                                ? number_format($price, 0, ',', '.')
                                                                : '';
                                                        }
                                                    ?>
                                                    <?php if($detail->studentReceipt->count()): ?>
                                                        <?php $__currentLoopData = $detail->studentReceipt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->index + 1); ?> </td>
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
                                                                <td><?php echo e(isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : ''); ?>

                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary"
                                                                        href="<?php echo e(route('receipt.print', $row->id)); ?>"
                                                                        data-toggle="tooltip" title="<?php echo app('translator')->get('In phiếu'); ?>"
                                                                        data-original-title="<?php echo app('translator')->get('In phiếu'); ?>"
                                                                        onclick="return openCenteredPopup(this.href)">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary btn_show_detail"
                                                                        data-toggle="tooltip"
                                                                        data-id="<?php echo e($row->id); ?>"
                                                                        data-url="<?php echo e(route('receipt.view', $row->id)); ?>"
                                                                        title="<?php echo app('translator')->get('Show'); ?>"
                                                                        data-original-title="<?php echo app('translator')->get('Show'); ?>">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>

                                                                    <a href="<?php echo e(route('receipt.show', $row->id)); ?>">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-warning"
                                                                            title="<?php echo app('translator')->get('Cập nhật'); ?>"
                                                                            data-original-title="<?php echo app('translator')->get('Cập nhật'); ?>">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </button>
                                                                    </a>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger btn_delete_receipt"
                                                                        data-id="<?php echo e($row->id); ?>">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>

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
                                            <p>Danh sách các CT Kh.Mãi</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo app('translator')->get('Chọn'); ?></th>
                                                        <th><?php echo app('translator')->get('Mã CT Kh.Mãi'); ?></th>
                                                        <th><?php echo app('translator')->get('Tên CT Kh.Mãi'); ?></th>
                                                        <th><?php echo app('translator')->get('Mô tả'); ?></th>
                                                        <th><?php echo app('translator')->get('Loại'); ?></th>
                                                        <th><?php echo app('translator')->get('Thời gian bắt đầu'); ?></th>
                                                        <th><?php echo app('translator')->get('Thời gian kết thúc'); ?></th>
                                                        <th><?php echo app('translator')->get('Chi tiết Kh.Mãi'); ?></th>
                                                        <th><?php echo app('translator')->get('Trạng thái'); ?></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $list_promotion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="radio" class="radio_promotion"
                                                                    <?php echo e(in_array($row->id, $promotion_active->pluck('promotion_id')->toArray()) ? 'disabled' : ''); ?>

                                                                    name="radio_promotion" value="<?php echo e($row->id); ?>">
                                                            </td>
                                                            <td class="code"><?php echo e($row->promotion_code ?? ''); ?></td>
                                                            <td class="name"><?php echo e($row->promotion_name ?? ''); ?></td>
                                                            <td class="des"><?php echo e($row->description ?? ''); ?></td>
                                                            <td class="type"><?php echo e(__($row->promotion_type)); ?></td>
                                                            <td><?php echo e(\Carbon\Carbon::parse($row->time_start)->format('d/m/Y') ?? ''); ?>

                                                            </td>
                                                            <td><?php echo e(\Carbon\Carbon::parse($row->time_end)->format('d/m/Y') ?? ''); ?>

                                                            </td>
                                                            <td class="service">
                                                                <?php $__currentLoopData = $row->json_params->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        $service_detail = $row
                                                                            ->getServices()
                                                                            ->find($val->service_id);
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
                                                            </td>
                                                            <td class="status">
                                                                <?php echo e(__($row->status)); ?>

                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>

                                            </table>
                                            <br>
                                            <p>CT Kh.Mãi được áp dụng</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>

                                                        <th><?php echo app('translator')->get('Chọn'); ?></th>
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
                                                    <?php $__currentLoopData = $promotion_active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td>

                                                            </td>
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
                                                                <?php $__currentLoopData = $row->promotion->json_params->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php

                                                                        $service_detail = $row->promotion
                                                                            ->getServices()
                                                                            ->find($val->service_id);
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
                                                            </td>
                                                            <td>
                                                                <?php echo e(__($row->status)); ?>

                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="promotion_new"></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-sm btn-success"><?php echo app('translator')->get('Danh sách'); ?></button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Modal Người thân-->
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="addParentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <form action="<?php echo e(route('student.addParent', $detail->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addParentModalLabel"><?php echo app('translator')->get('Chọn người thân'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search-parent"
                                placeholder="<?php echo app('translator')->get('Tìm theo tên phụ huynh...'); ?>">
                        </div>
                        <div class="table-wrapper table-responsive">
                            <table class="table table-hover table-bordered" id="parent-table">
                                <thead>
                                    <tr>
                                        <th>Chọn</th>
                                        <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                        <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                        <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                        <th><?php echo app('translator')->get('Email'); ?></th>
                                        <th><?php echo app('translator')->get('Chọn mối quan hệ'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $allParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isChecked = in_array($parent->id, $studentParentIds);
                                            $existingRelation = $detail->studentParents->firstWhere(
                                                'parent_id',
                                                $parent->id,
                                            );
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="parents[<?php echo e($parent->id); ?>][id]"
                                                    value="<?php echo e($parent->id); ?>" <?php echo e($isChecked ? 'checked' : ''); ?>>
                                            </td>
                                            <td class="parent-name"><?php echo e($parent->first_name); ?> <?php echo e($parent->last_name); ?>

                                            </td>
                                            <td><?php echo app('translator')->get($parent->sex); ?></td>
                                            <td><?php echo e($parent->phone); ?></td>
                                            <td><?php echo e($parent->email); ?></td>
                                            <td>
                                                <select style="width:100%"
                                                    name="parents[<?php echo e($parent->id); ?>][relationship_id]"
                                                    class="form-control select2">
                                                    <?php $__currentLoopData = $list_relationship; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option
                                                            <?php echo e($existingRelation && $existingRelation->relationship_id == $relation->id ? 'selected' : ''); ?>

                                                            value="<?php echo e($relation->id); ?>"><?php echo e($relation->title); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Lưu người thân đã chọn'); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal dịch vụ-->
    <div data-backdrop="static" class="modal fade" id="addServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
            <form id="submitstudentaddService" action="<?php echo e(route('student.addService', $detail->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel"><?php echo app('translator')->get('Chọn dịch vụ'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search-service"
                                placeholder="<?php echo app('translator')->get('Tìm theo tên dịch vụ...'); ?>">
                        </div>
                        <div class="table-wrapper table-responsive">
                            <table class="table table-hover table-bordered" id="service-table">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Tính chất dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                        <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                        <th><?php echo app('translator')->get('Chu kỳ thu'); ?></th>
                                        <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                        <th>Chọn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $unregisteredServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="service-name"><?php echo e($service->name ?? ''); ?></td>
                                            <td><?php echo e($service->service_category->name ?? ''); ?></td>
                                            <td><?php echo e($service->is_attendance == 0 ? 'Không theo điểm danh' : 'Tính theo điểm danh'); ?>

                                            </td>
                                            <td><?php echo e(__($service->service_type ?? '')); ?></td>

                                            <td>
                                                <?php if(isset($service->serviceDetail) && $service->serviceDetail->count() > 0): ?>
                                                    <?php $__currentLoopData = $service->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <ul>
                                                            <li>Số tiền:
                                                                <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?>

                                                            </li>
                                                            <li>Số lượng: <?php echo e($detail_service->quantity ?? ''); ?></li>
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
                                                <select style="width:100%"
                                                    name="services[<?php echo e($service->id); ?>][payment_cycle_id]"
                                                    class="form-control select2">
                                                    <?php $__currentLoopData = $list_payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php echo e($payment_cycle->is_default == 1 ? 'selected' : ''); ?>

                                                            value="<?php echo e($payment_cycle->id); ?>">
                                                            <?php echo e($payment_cycle->name ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="services[<?php echo e($service->id); ?>][note]" value=""
                                                    placeholder="<?php echo app('translator')->get('Ghi chú'); ?>">
                                            </td>
                                            <td>
                                                <input type="checkbox" name="services[<?php echo e($service->id); ?>][id]"
                                                    value="<?php echo e($service->id); ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Lưu dịch vụ đã chọn'); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal tái tục-->
    <div data-backdrop="static" class="modal fade" id="reincarnationModal" tabindex="-1" role="dialog"
        aria-labelledby="reincarnationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formRenew" action="<?php echo e(route('receipt.calculateStudent.renew')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reincarnationModalLabel"><?php echo app('translator')->get('Tái tục dịch vụ cho học sinh'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày bắt đầu chu kỳ thanh toán'); ?> <small class="text-danger">*</small></label>
                                <input class="form-control" type="date" name="enrolled_at" value="" required>
                                <input type="hidden" name="student_id" value="<?php echo e($detail->id); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Tính toán tái tục dịch vụ'); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="updateStudentServiceForm" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editServiceModalLabel"><?php echo app('translator')->get('Cập nhật dịch vụ'); ?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <select name="payment_cycle_service" style="width:100%" class="form-control select2">
                                        <?php $__currentLoopData = $list_payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($payment_cycle->id); ?>"><?php echo e($payment_cycle->name ?? ''); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <input type="text" class="form-control" name="note_service" value=""
                                        placeholder="<?php echo app('translator')->get('Ghi chú'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnUpdateService" type="button" class="btn btn-primary"><?php echo app('translator')->get('Cập nhật'); ?></button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    
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
        $(document).on('change', '.radio_promotion', function() {
            var _row = $(this).closest('tr');
            var id = _row.val();
            var code = _row.find('.code').html();
            var name = _row.find('.name').html();
            var des = _row.find('.des').html();
            var type = _row.find('.type').html();
            var service = _row.find('.service').html();

            var _html = `
            <td>
                <button type="button"
                    class="btn btn-sm btn-danger delete_student_promotion"
                    onclick="$(this).closest('tr').html(''); $('.radio_promotion').prop('checked', false);">
                    <i class="fa fa-close"></i> Hủy
                </button>
                <input type="hidden" name="promotion_student[promotion_id]" value="${id}">
            </td>
            <td>${code}</td>
            <td>${name}</td>
            <td>${des}</td>
            <td>${type}</td>
            <td>
                <input required type="date" name="promotion_student[time_start]"
                    class="form-control"
                    value="">

            </td>
            <td>
                <input required type="date" name="promotion_student[time_end]"
                    class="form-control"
                    value="">
            </td>
            <td>${service}</td>
            <td>
                <select name="promotion_student[status]" class="form-control select2 w-100">
                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>">
                            <?php echo e(__($val)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
            `;
            $('.promotion_new').html(_html);
            // $('.select2').select2();
            $('html, body').animate({
                scrollTop: $(".promotion_new").offset().top
            }, 1000);
        })


        $('#search-parent').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#parent-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.parent-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
        $('#search-service').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#service-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.service-name').text().toLowerCase().indexOf(value) > -1);
            });
        });


        $('.delete_student_service').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn xóa dịch vụ này khỏi học sinh ?')) {
                let _id = $(this).attr('data-id');
                let url = "<?php echo e(route('delete_student_service')); ?>/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        if (response.message === 'success') {
                            localStorage.setItem('activeTab', '#tab_3');
                            location.reload();
                        } else {
                            alert("Bạn không có quyền thao tác dữ liệu");
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
        $('.update_student_service').click(function(e) {
            e.preventDefault();
            let _id = $(this).data('id');
            let url = "<?php echo e(route('get_student_service_info')); ?>";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                },
                success: function(response) {
                    if (response.success) {
                        $('#editServiceModal input[name="note"]').val(response.data.note);
                        $('#editServiceModal select').val(response.data.payment_cycle_id).trigger(
                            'change');
                        // Mở modal
                        $('#editServiceModal').modal('show');
                        $('#btnUpdateService').attr('data-id', _id);
                    } else {
                        alert("Không tìm thấy dữ liệu dịch vụ.");
                    }
                },
                error: function(response) {
                    alert("Đã có lỗi xảy ra khi tải dữ liệu.");
                }
            });
        });

        $('#btnUpdateService').click(function() {
            let cycleValue = $('select[name="payment_cycle_service"]').val();
            let noteValue = $('input[name="note_service"]').val();
            let currentStudentServiceId = $(this).data('id'); // Lấy ID dịch vụ hiện tại từ nút cập nhật
            $.ajax({
                type: "POST",
                url: "<?php echo e(route('student.updateService.ajax')); ?>",
                data: {
                    id: currentStudentServiceId,
                    note: noteValue,
                    payment_cycle_id: cycleValue,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.message === 'success') {
                        $('#editServiceModal').modal('hide');
                        localStorage.setItem('activeTab', '#tab_3');
                        location.reload();
                    } else {
                        alert("Không có quyền thao tác.");
                    }
                },
                error: function() {
                    alert("Lỗi cập nhật.");
                }
            });
        });
        $('.btn_delete_receipt').click(function() {
            let currentStudentReceiptId = $(this).data('id'); // Lấy ID phiếu thu hiện tại từ nút
            if (confirm("Bạn có chắc chắn muốn xóa phiếu thu này?")) {
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('student.deleteReceipt')); ?>",
                    data: {
                        id: currentStudentReceiptId, // Đảm bảo đúng biến được gửi đi
                    },
                    success: function(response) {
                        if (response.message === 'success') {
                            localStorage.setItem('activeTab', '#tab_4');
                            location.reload();
                        } else {
                            alert("Bạn không có quyền thao tác dữ liệu");
                        }
                    },
                    error: function() {
                        alert("Lỗi cập nhật.");
                    }
                });
            }
        });


        $(document).ready(function() {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                // Bỏ class active hiện tại
                $('.nav-tabs li, .tab-content .tab-pane').removeClass('active');

                // Thêm active cho tab tương ứng
                $('.nav-tabs li a[href="' + activeTab + '"]').parent().addClass('active');
                $(activeTab).addClass('active');

                // Xoá dữ liệu đã lưu để tránh kích hoạt lại lần sau
                localStorage.removeItem('activeTab');
            }
        });

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

        $('#submitstudentaddService').on('submit', function() {
            localStorage.setItem('activeTab', '#tab_3');
        });

        $('#formRenew').on('submit', function() {
            localStorage.setItem('activeTab', '#tab_4');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/students/edit.blade.php ENDPATH**/ ?>