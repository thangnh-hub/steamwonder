


<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .modal-header{
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
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="box-body">
                                            <div class="d-flex-wap">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Khu vực'); ?><small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value=""><?php echo app('translator')->get('Chọn khu vực'); ?></option>
                                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val->id); ?>" <?php echo e(old('area_id', $detail->area_id) == $val->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($val->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Last Name'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name', $detail->last_name)); ?>" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('First Name'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name', $detail->first_name)); ?>" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên thường gọi'); ?></label>
                                                        <input type="text" class="form-control" name="nickname" value="<?php echo e(old('nickname', $detail->nickname)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Giới tính'); ?></label>
                                                        <select name="sex" class="form-control select2">
                                                            <?php $__currentLoopData = $list_sex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('sex', $detail->sex) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày sinh'); ?></label>
                                                        <input type="date" class="form-control" name="birthday" value="<?php echo e(old('birthday', $detail->birthday)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày nhập học'); ?></label>
                                                        <input type="date" class="form-control" name="enrolled_at" value="<?php echo e(old('enrolled_at', $detail->enrolled_at)); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                        <select name="status" class="form-control select2">
                                                            <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status) == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Chính sách được hưởng'); ?></label>
                                                        <select name="policies[]" class="form-control select2" multiple>
                                                            <?php
                                                                $selectedPolicies = $detail->studentPolicies->pluck('policy_id')->toArray();
                                                            ?>
                                                            <?php $__currentLoopData = $list_policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($policy->id); ?>" <?php echo e(in_array($policy->id, $detail->studentPolicies->pluck('policy_id')->toArray()) ? 'selected' : ''); ?>>
                                                                    <?php echo e($policy->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Chu kỳ thu dịch vụ'); ?></label>
                                                        <select name="payment_cycle_id" class="form-control select2">
                                                            <option value="">Chọn</option>
                                                            <?php $__currentLoopData = $list_payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option <?php echo e(old('payment_cycle_id', $detail->payment_cycle_id) == $payment_cycle->id ? 'selected' : ''); ?> value="<?php echo e($payment_cycle->id); ?>"><?php echo e($payment_cycle->name ?? ""); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group box_img_right">
                                                        <label><?php echo app('translator')->get('Ảnh đại diện'); ?></label>
                                                        <div id="image-holder">
                                                            <img src="<?php echo e(!empty($detail->avatar) ? asset($detail->avatar) : url('themes/admin/img/no_image.jpg')); ?>" style="max-height: 120px;">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm" data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden" type="hidden" name="avatar"
                                                                placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e(old('avatar', $detail->avatar)); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="tab-pane " id="tab_2">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addParentModal">
                                                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('Cập nhật người thân'); ?>
                                                </button>     
                                            </div>
                                            
                                            <br>
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
                                                                    <img src="<?php echo e(asset($row->parent->avatar)); ?>" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                <?php else: ?>
                                                                    <span class="text-muted">No image</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a target="_blank" href="<?php echo e(route('parents.show', $row->parent->id)); ?>">
                                                                    <?php echo e($row->parent->first_name ?? ''); ?> <?php echo e($row->parent->last_name ?? ''); ?>  
                                                                </a>
                                                            </td>
                                                            <td><?php echo app('translator')->get($row->parent->sex ?? ''); ?></td>
                                                            <td><?php echo e($row->parent->birthday ? \Carbon\Carbon::parse($row->parent->birthday)->format('d/m/Y') : ''); ?></td>
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
                                                            <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                                
                                            </table>
                                            
                                        </div>                      
                                    </div>
                                    
                                    <div class="tab-pane " id="tab_3">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addServiceModal">
                                                    <i class="fa fa-plus"></i> <?php echo app('translator')->get('Đăng ký dịch vụ'); ?>
                                                </button>     
                                                    
                                            </div>
                                            
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
                                                        <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $activeServices = $detail->studentServices->where('status', 'active');
                                                    ?>
                                                    <?php if($activeServices->count()): ?>
                                                    <?php $__currentLoopData = $activeServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->index + 1); ?></td>
                                                        <td><?php echo e($row->services->name ?? ""); ?></td>
                                                        <td><?php echo e($row->services->service_category->name ?? ""); ?></td>
                                                        <td><?php echo e($row->services->education_program->name ?? ""); ?></td>
                                                        <td><?php echo e($row->services->education_age->name ?? ""); ?></td>
                                                        <td><?php echo e($row->services->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh"); ?></td>
                                                        <td><?php echo e(__($row->services->service_type??"")); ?></td>
                                                        
                                                        <td>
                                                            <?php if(isset($row->services->serviceDetail) && $row->services->serviceDetail->count() > 0): ?>
                                                            <?php $__currentLoopData = $row->services->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <ul>
                                                                <li>Số tiền: <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?></li>
                                                                <li>Số lượng: <?php echo e($detail_service->quantity ?? ''); ?></li>
                                                                <li>Từ: <?php echo e((isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '')); ?></li>
                                                                <li>Đến: <?php echo e((isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '')); ?></li>
                                                            </ul>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo e($row->paymentcycle->name ?? ""); ?>

                                                        </td>
                                                        <td>
                                                            <?php echo e($row->json_params->note ?? ""); ?>

                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-danger delete_student_service" data-id="<?php echo e($row->id); ?>">
                                                                <i class="fa fa-close"></i> Hủy
                                                            </button>
                                                            <button data-id="<?php echo e($row->id); ?>" type="button" class="btn btn-primary btn-sm update_student_service" data-toggle="modal" data-target="#editServiceModal">
                                                                <i class="fa fa-pencil"></i> <?php echo app('translator')->get('Cập nhật'); ?>
                                                            </button> 
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
                                                $cancelledServices = $detail->studentServices->where('status', 'cancelled');
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
                                                                <?php echo e(optional($row->services->serviceDetail->first())->start_at 
                                                                    ? \Carbon\Carbon::parse($row->services->serviceDetail->first()->start_at)->format('d-m-Y') 
                                                                    : ''); ?>

                                                            </td>
                                                            <td>
                                                                <?php echo e(optional($row->services->serviceDetail->first())->end_at 
                                                                    ? \Carbon\Carbon::parse($row->services->serviceDetail->first()->end_at)->format('d-m-Y') 
                                                                    : ''); ?>

                                                            </td>
                                                            <td>
                                                                <?php echo e($row->adminUpdated->name ?? ""); ?> (<?php echo e($row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('H:i:s d-m-Y') : ''); ?>)   
                                                            </td>
                                                            <td><span class="badge badge-danger">Đã huỷ</span></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                            <?php endif; ?>
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
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="addParentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
        <form action="<?php echo e(route('student.addParent', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel"><?php echo app('translator')->get('Chọn người thân'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search-parent" placeholder="<?php echo app('translator')->get('Tìm theo tên phụ huynh...'); ?>">
                    </div>
                    <div class="table-wrapper">
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
                                    $existingRelation = $detail->studentParents->firstWhere('parent_id', $parent->id);
                                ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="parents[<?php echo e($parent->id); ?>][id]" value="<?php echo e($parent->id); ?>" <?php echo e($isChecked ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="parent-name"><?php echo e($parent->first_name); ?> <?php echo e($parent->last_name); ?></td>
                                        <td><?php echo app('translator')->get($parent->sex); ?></td>
                                        <td><?php echo e($parent->phone); ?></td>
                                        <td><?php echo e($parent->email); ?></td>
                                        <td>
                                            <select style="width:100%" name="parents[<?php echo e($parent->id); ?>][relationship_id]" class="form-control select2">
                                                <?php $__currentLoopData = $list_relationship; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e($existingRelation && $existingRelation->relationship_id == $relation->id ? 'selected' : ''); ?> value="<?php echo e($relation->id); ?>"><?php echo e($relation->title); ?></option>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <!-- Modal dịch vụ-->
    <div data-backdrop="static" class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
        <form action="<?php echo e(route('student.addService', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel"><?php echo app('translator')->get('Chọn dịch vụ'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search-service" placeholder="<?php echo app('translator')->get('Tìm theo tên dịch vụ...'); ?>">
                    </div>
                    <div class="table-wrapper" >
                        <table class="table table-hover table-bordered" id="service-table">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Tên dịch vụ'); ?></th>
                                    <th><?php echo app('translator')->get('Nhóm dịch vụ'); ?></th>
                                    <th><?php echo app('translator')->get('Tính chất dịch vụ'); ?></th>
                                    <th><?php echo app('translator')->get('Loại dịch vụ'); ?></th>
                                    <th><?php echo app('translator')->get('Biểu phí'); ?></th>
                                    
                                    <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $unregisteredServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                    <tr>
                                        <td class="service-name"><?php echo e($service->name ?? ""); ?></td>
                                        <td><?php echo e($service->service_category->name ?? ""); ?></td>
                                        <td><?php echo e($service->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh"); ?></td>
                                        <td><?php echo e(__($service->service_type??"")); ?></td>
                                        
                                        <td>
                                            <?php if(isset($service->serviceDetail) && $service->serviceDetail->count() > 0): ?>
                                                <?php $__currentLoopData = $service->serviceDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <ul>
                                                    <li>Số tiền: <?php echo e(isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : ''); ?></li>
                                                    <li>Số lượng: <?php echo e($detail_service->quantity ?? ''); ?></li>
                                                    <li>Từ: <?php echo e((isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '')); ?></li>
                                                    <li>Đến: <?php echo e((isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '')); ?></li>
                                                </ul>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td>
                                            <input type="text" class="form-control" name="services[<?php echo e($service->id); ?>][note]" value="" placeholder="<?php echo app('translator')->get('Ghi chú'); ?>">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="services[<?php echo e($service->id); ?>][id]" value="<?php echo e($service->id); ?>" >
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Lưu dịch vụ đã chọn'); ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </form>
        </div>
    </div>

    
    <div  class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <form id="updateStudentServiceForm" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel"><?php echo app('translator')->get('Cập nhật dịch vụ'); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Ghi chú</label>
                                <input type="text" class="form-control" name="note" value="" placeholder="<?php echo app('translator')->get('Ghi chú'); ?>">
                            </div>
                        </div>           
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnUpdateService" type="button" class="btn btn-primary"><?php echo app('translator')->get('Cập nhật'); ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->get('Đóng'); ?></button>
                </div>
            </div>
        </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
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

        $('#btnUpdateService').click(function () {
            let noteValue = $('input[name="note"]').val();
            let currentStudentServiceId = $(this).data('id'); // Lấy ID dịch vụ hiện tại từ nút cập nhật
            $.ajax({
                type: "POST",
                url: "<?php echo e(route('student.updateService.ajax')); ?>",
                data: {
                    id: currentStudentServiceId,
                    note: noteValue,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.message === 'success') {
                        $('#editServiceModal').modal('hide');
                        localStorage.setItem('activeTab', '#tab_3');
                        location.reload();
                    } else {
                        alert("Không thể cập nhật ghi chú.");
                    }
                },
                error: function() {
                    alert("Lỗi cập nhật ghi chú.");
                }
            });
        });

        $(document).ready(function () {
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/students/edit.blade.php ENDPATH**/ ?>