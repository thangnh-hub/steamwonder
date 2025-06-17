<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .gallery-image img {
            width: 150px !important;
            height: 150px !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-dm btn-success pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
            </a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if(session('successMessage')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('successMessage')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('errorMessage')): ?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('errorMessage')); ?>

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
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $admin->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Update form'); ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5><?php echo app('translator')->get('Thông tin chính'); ?> <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5><?php echo app('translator')->get('Khu vực dữ liệu được xem'); ?> (<?php echo app('translator')->get('Nếu có'); ?>)</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5><?php echo app('translator')->get('Chức năng mở rộng'); ?> (<?php echo app('translator')->get('Chỉ IT cấu hình'); ?>)</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_4" data-toggle="tab">
                                            <h5><?php echo app('translator')->get('Menu mở rộng'); ?> (<?php echo app('translator')->get('Chỉ IT cấu hình'); ?>)</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_5" data-toggle="tab">
                                            <h5><?php echo app('translator')->get('Ảnh CV'); ?></h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Email'); ?> <small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="<?php echo e($admin->email); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Password'); ?> <small
                                                            class="text-muted"><i>(<?php echo app('translator')->get("Skip if you don't want to change your password"); ?>)</i></small></label>
                                                    <input type="password" class="form-control" name="password_new"
                                                        placeholder="<?php echo app('translator')->get('Password must be at least 8 characters'); ?>" value=""
                                                        autocomplete="new-password">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mã nhân viên'); ?> </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="<?php echo app('translator')->get('Mã Code'); ?>" name="admin_code"
                                                        value="<?php echo e(old('admin_code') ?? $admin->admin_code); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Full name'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="<?php echo e($admin->name); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Phone'); ?> </label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="<?php echo app('translator')->get('Phone'); ?>"
                                                        value="<?php echo e(old('phone') ?? $admin->phone); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Birthday'); ?> </label>
                                                    <input type="date" class="form-control" name="birthday"
                                                        placeholder="<?php echo app('translator')->get('Birthday'); ?>"
                                                        value="<?php echo e(old('birthday') ?? (\Carbon\Carbon::parse($admin->birthday)->format('Y-m-d') ?? '')); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Position'); ?> </label>
                                                    <input type="text" class="form-control" name="json_params[position]"
                                                        placeholder="<?php echo app('translator')->get('Position'); ?>"
                                                        value="<?php echo e(old('json_params[position]') ?? ($admin->json_params->position ?? '')); ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Address'); ?></label>
                                                    <textarea name="json_params[address]" class="form-control" rows="5"><?php echo e($admin->json_params->address ?? old('json_params[address]')); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Brief'); ?></label>
                                                    <textarea name="json_params[brief]" class="form-control" rows="5"><?php echo e($admin->json_params->brief ?? old('json_params[brief]')); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Content'); ?></label>
                                                        <textarea name="json_params[content]" class="form-control" id="content_vi"><?php echo e($admin->json_params->content ?? old('json_params[content]')); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <ul class="checkbox_list">
                                                        <?php
                                                            $checked = '';
                                                            if (
                                                                !empty($admin->json_params->area_id) &&
                                                                in_array($items->id, $admin->json_params->area_id)
                                                            ) {
                                                                $checked = 'checked';
                                                            }
                                                        ?>
                                                        <li>
                                                            <input name="json_params[area_id][]" type="checkbox"
                                                                value="<?php echo e($items->id); ?>"
                                                                id="json_access_menu_id_<?php echo e($items->id); ?>"
                                                                class="mr-15" <?php echo e($checked); ?>>
                                                            <label for="json_access_menu_id_<?php echo e($items->id); ?>"><strong><?php echo e(__($items->code)); ?>

                                                                    - <?php echo e(__($items->name)); ?></strong></label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_3">

                                        <div class="masonry-container">
                                            <?php if(count($activeModules) == 0): ?>
                                                <p>
                                                    <?php echo app('translator')->get('No record found on the system!'); ?>
                                                </p>
                                            <?php else: ?>
                                                <?php $__currentLoopData = $activeModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="masonry-box-item">
                                                        <ul class="checkbox_list">
                                                            <li>
                                                                <label
                                                                    for="json_access_module_code_<?php echo e($module->id); ?>"><strong><?php echo e(__($module->name)); ?></strong></label>
                                                            </li>
                                                            <?php $__currentLoopData = $module->moduleFunctions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $func): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(isset($admin->permission_access_by_role->function_code) &&
                                                                        in_array($func->function_code, $admin->permission_access_by_role->function_code)): ?>
                                                                    <li>
                                                                        <input type="checkbox" class="mr-15" checked
                                                                            disabled>
                                                                        <label style="font-style: italic;"
                                                                            class="text-danger"
                                                                            for="json_access_function_code_<?php echo e($func->id); ?>"><?php echo e(__($func->name)); ?>

                                                                            (<?php echo e($func->function_code ?? ''); ?>)
                                                                        </label>
                                                                    </li>
                                                                <?php else: ?>
                                                                    <?php
                                                                        $checked = '';
                                                                        if (
                                                                            isset($admin->json_params->function_code) &&
                                                                            in_array(
                                                                                $func->function_code,
                                                                                $admin->json_params->function_code,
                                                                            )
                                                                        ) {
                                                                            $checked = 'checked';
                                                                        }
                                                                    ?>
                                                                    <li>
                                                                        <input name="json_params[function_code][]"
                                                                            type="checkbox"
                                                                            value="<?php echo e($func->function_code); ?>"
                                                                            id="json_access_function_code_<?php echo e($func->id); ?>"
                                                                            class="mr-15" <?php echo e($checked); ?>>
                                                                        <label
                                                                            for="json_access_function_code_<?php echo e($func->id); ?>"><?php echo e(__($func->name)); ?>

                                                                            (<?php echo e($func->function_code ?? ''); ?>)
                                                                        </label>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <div class="masonry-container">
                                            <?php if(count($activeMenus) == 0): ?>
                                                <div class="col-12">
                                                    <?php echo app('translator')->get('No record found on the system!'); ?>
                                                </div>
                                            <?php else: ?>
                                                <?php $__currentLoopData = $activeMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="masonry-box-item">
                                                        <ul class="checkbox_list">
                                                            <?php echo $__env->make('admin.pages.admins.role-menu-item', [
                                                                'menu' => $menu,
                                                                'selectedMenus' => array_merge(
                                                                    $admin->permission_access_by_role->menu_id ??
                                                                        [],
                                                                    $admin->json_params->menu_id ?? []),
                                                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </ul>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                        </div>

                                    </div>
                                    <div class="tab-pane " id="tab_5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="btn btn-warning btn-sm add-gallery-image"
                                                        data-toggle="tooltip" title="Nhấn để chọn thêm ảnh"
                                                        type="button" value="Thêm ảnh" />
                                                </div>
                                                <div class="row list-gallery-image">
                                                    <?php if(isset($admin->json_params->gallery_image)): ?>
                                                        <?php $__currentLoopData = $admin->json_params->gallery_image; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($value != null): ?>
                                                                <div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">
                                                                    <div id="avatar-holder-<?php echo e($key); ?>">
                                                                        <img width="150px" height="150px" class="img-width"
                                                                            src="<?php echo e($value); ?>">
                                                                    </div>
                                                                    <input type="text"
                                                                        name="json_params[gallery_image][<?php echo e($key); ?>]"
                                                                        class="hidden" id="gallery_image_<?php echo e($key); ?>"
                                                                        value="<?php echo e($value); ?>">
                                                                    <div class="btn-action">
                                                                        <span
                                                                            class="btn btn-sm btn-success btn-upload lfm mr-5"
                                                                            data-input="gallery_image_<?php echo e($key); ?>"
                                                                            data-preview="avatar-holder-<?php echo e($key); ?>">
                                                                            <i class="fa fa-upload"></i>
                                                                        </span>
                                                                        <span class="btn btn-sm btn-danger btn-remove">
                                                                            <i class="fa fa-trash"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Status'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2 <?php echo e($admin->status); ?>">
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($admin->status) && $admin->status == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Gender'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="gender" class=" form-control select2">
                                    <?php $__currentLoopData = $gender; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($admin->gender) && $admin->gender == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Role'); ?> <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="role" id="role" class="form-control select2" required>
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($admin->role) && $admin->role == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Quyền mở rộng'); ?></label>
                                <?php
                                    $arr_role_extend = (array) ($admin->json_params->role_extend ?? []);
                                ?>
                                <select name="json_params[role_extend][]" id="role_extend" class="form-control select2"
                                    multiple="multiple" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(in_array($item->id, $arr_role_extend) ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Admin type'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="admin_type" class="admin_type form-control select2">
                                    <?php $__currentLoopData = $admin_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($admin->admin_type) && $admin->admin_type == $val ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="teacher_type" class="teacher_type form-control select2">
                                    <?php $__currentLoopData = $teacher_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($admin->teacher_type) && $admin->teacher_type == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>


                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Thuộc khu vực (nếu có)'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="area_id" class=" form-control select2">
                                    <option value="" selected disabled><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($items->id); ?>"
                                            <?php echo e(isset($admin->area_id) && $admin->area_id == $items->id ? 'selected' : ''); ?>>
                                            <?php echo e(__($items->code)); ?>

                                            - <?php echo e(__($items->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select name="department_id" class="form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e($admin->department_id == $val->id ? 'selected' : ''); ?>

                                            value="<?php echo e($val->id); ?>">
                                            <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Direct manager'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="parent_id" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $direct_manager; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($admin->parent_id) && $admin->parent_id == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Avatar'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right <?php echo e(isset($admin->avatar) ? 'active' : ''); ?>">
                                <div id="avatar-holder">
                                    <?php if(isset($admin->avatar) && $admin->avatar != ''): ?>
                                        <img src="<?php echo e($admin->avatar); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo e(url('themes/admin/img/no_image.jpg')); ?>">
                                    <?php endif; ?>
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="avatar" data-preview="avatar-holder" class="btn btn-primary lfm"
                                            data-type="cms-avatar">
                                            <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                        </a>
                                    </span>
                                    <input id="avatar" class="form-control inp_hidden" type="hidden" name="avatar"
                                        placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e($admin->avatar ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                    <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            $('.admin_type').trigger('change');
            var no_image_link = '<?php echo e(url('themes/admin/img/no_image.jpg')); ?>';
            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.inp_hidden').val("");
            });

            CKEDITOR.replace('content_vi', ck_options);
            var no_image_link = '<?php echo e(url('themes/admin/img/no_image.jpg')); ?>';

            $('.add-gallery-image').click(function(event) {
                let keyRandom = new Date().getTime();
                let elementParent = $('.list-gallery-image');
                let elementAppend =
                    '<div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">';
                elementAppend += '<div id="gallery-holder-' + keyRandom +
                    '"><img width="150px" height="150px" class="img-width"';
                elementAppend += 'src="' + no_image_link + '"> </div>';
                elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
                    ']" class="hidden" id="gallery_image_' + keyRandom +
                    '">';
                elementAppend += '<div class="btn-action">';
                elementAppend +=
                    '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                    keyRandom +
                    '" data-type="cms-image" data-preview="gallery-holder-' + keyRandom + '">';
                elementAppend += '<i class="fa fa-upload"></i>';
                elementAppend += '</span>';
                elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
                elementAppend += '<i class="fa fa-trash"></i>';
                elementAppend += '</span>';
                elementAppend += '</div>';
                elementParent.append(elementAppend);

                $('.lfm').filemanager('Images', {
                    prefix: route_prefix
                });
                // $('.lfm').filemanager('Images', {
                //     prefix: '<?php echo e(route('ckfinder_browser')); ?>'
                // });
            });
            // Change image for img tag gallery-image
            $('.list-gallery-image').on('change', 'input', function() {
                let _root = $(this).closest('.gallery-image');
                var img_path = $(this).val();
                _root.find('img').attr('src', img_path);
            });

            // Delete image
            $('.list-gallery-image').on('click', '.btn-remove', function() {
                // if (confirm("<?php echo app('translator')->get('confirm_action'); ?>")) {
                let _root = $(this).closest('.gallery-image');
                _root.remove();
                // }
            });

            $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
                $(this).find('.btn-action').show();
            });
            $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
                $(this).find('.btn-action').hide();
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/admins/edit.blade.php ENDPATH**/ ?>