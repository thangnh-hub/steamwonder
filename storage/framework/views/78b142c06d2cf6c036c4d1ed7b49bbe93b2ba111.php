

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .font-bold{
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Họ tên học viên'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái hồ sơ'); ?></label>
                                <select name="is_type" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $type_profile; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['is_type']) && $params['is_type'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($item); ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
            </div>
            <div class="box-body table-responsive">
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

                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Học viên'); ?></th>
                                <th style="width:120px"><?php echo app('translator')->get('Ảnh CV'); ?></th>
                                <th><?php echo app('translator')->get('Tiêu đề CV'); ?></th>
                                <th><?php echo app('translator')->get('Mục đã điền'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái hồ sơ'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px;"><?php echo e($row->user->name ?? ''); ?></strong>
                                    </td>
                                    <td>
                                        <img style="width:80px;height:120px" src="<?php echo e(isset($row->json_params->upload_image->avatar)?$row->json_params->upload_image->avatar:asset('themes/admin/img/no_image.jpg')); ?>" >
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"> <?php echo e($row->cv_title ?? ''); ?></a>    
                                    </td>
                                    
                                    <td>
                                        <ul>
                                            <?php
                                                $text_anh = isset($row->json_params->upload_image->avatar) ? "text-success" :"text-danger";
                                                $icon_anh = isset($row->json_params->upload_image->avatar) ? "fa-check-square" :"fa-window-close";

                                                $text_cv = isset($row->json_params->profile) ? "text-success" :"text-danger";
                                                $icon_cv = isset($row->json_params->profile) ? "fa-check-square" :"fa-window-close";

                                                $text_thudongluc = isset($row->json_params->hobby->letter) ? "text-success" :"text-danger";
                                                $icon_thudongluc = isset($row->json_params->hobby->letter) ? "fa-check-square" :"fa-window-close";

                                                $text_hochieu = isset($row->json_params->upload_image->passport_images) ? "text-success" :"text-danger";
                                                $icon_hochieu = isset($row->json_params->upload_image->passport_images) ? "fa-check-square" :"fa-window-close";

                                                $text_cap3 = isset($row->json_params->upload_image->diploma_image) ? "text-success" :"text-danger";
                                                $icon_cap3 = isset($row->json_params->upload_image->diploma_image) ? "fa-check-square" :"fa-window-close";

                                                $text_tiengduc = isset($row->json_params->upload_image->germany_images) ? "text-success" :"text-danger";
                                                $icon_tiengduc = isset($row->json_params->upload_image->germany_images) ? "fa-check-square" :"fa-window-close";

                                                $text_video = isset($row->json_params->upload_image->other_file) ? "text-success" :"text-danger";
                                                $icon_video = isset($row->json_params->upload_image->other_file) ? "fa-check-square" :"fa-window-close";

                                                $text_chuky = isset($row->json_params->upload_image->signature_image) ? "text-success" :"text-danger";
                                                $icon_chuky = isset($row->json_params->upload_image->signature_image) ? "fa-check-square" :"fa-window-close";
                                            ?>
                                            <li class="<?php echo e($text_anh); ?>"><?php echo app('translator')->get('Ảnh'); ?> <i class="fa <?php echo e($icon_anh); ?>"></i> </li>
                                            <li class="<?php echo e($text_cv); ?>"><?php echo app('translator')->get('CV'); ?> <i class="fa <?php echo e($icon_cv); ?>"></i> </li>
                                            <li class="<?php echo e($text_thudongluc); ?>"><?php echo app('translator')->get('Thư động lực'); ?> <i class="fa <?php echo e($icon_thudongluc); ?>"></i> </li>
                                            <li class="<?php echo e($text_hochieu); ?>"><?php echo app('translator')->get('Hộ chiếu'); ?> <i class="fa <?php echo e($icon_hochieu); ?>"></i> </li>
                                            <li class="<?php echo e($text_cap3); ?>"><?php echo app('translator')->get('Bằng THPT'); ?> <i class="fa <?php echo e($icon_cap3); ?>"></i> </li>
                                            <li class="<?php echo e($text_tiengduc); ?>"><?php echo app('translator')->get('Chứng chỉ tiếng Đức'); ?> <i class="fa <?php echo e($icon_tiengduc); ?>"></i> </li>
                                            <li class="<?php echo e($text_video); ?>"><?php echo app('translator')->get('Video'); ?> <i class="fa <?php echo e($icon_video); ?>"></i> </li>
                                            <li class="<?php echo e($text_chuky); ?>"><?php echo app('translator')->get('Chữ ký'); ?> <i class="fa <?php echo e($icon_chuky); ?>"></i> </li>
                                            
                                        </ul>
                                    </td>
                                    <td>
                                        <?php echo e(__($row->status ?? '')); ?>

                                    </td>
                                    
                                    <td>
                                        <div class="d-flex-wap" style="gap:5px">
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Edit'); ?>" data-original-title="<?php echo app('translator')->get('Edit'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>"
                                                method="POST" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy <?php echo e($rows->total()); ?> kết quả
                    </div>
                    <div class="col-sm-7">
                        <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\steamwonders\resources\views/admin/pages/profiles/index.blade.php ENDPATH**/ ?>