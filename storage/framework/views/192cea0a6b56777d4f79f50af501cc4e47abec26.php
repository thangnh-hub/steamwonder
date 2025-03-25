

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i>
                <?php echo app('translator')->get('Thêm mới người dùng'); ?></a>
            <form style="margin-right: 10px" class=" pull-right" action="<?php echo e(route('export_user')); ?>" method="get"
                enctype="multipart/form-data">

                <input type="hidden" name="keyword" value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                <input type="hidden" name="role" value="<?php echo e(isset($params['role']) ? $params['role'] : ''); ?>">
                <input type="hidden" name="admin_type"
                    value="<?php echo e(isset($params['admin_type']) ? $params['admin_type'] : ''); ?>">
                <input type="hidden" name="area_id" value="<?php echo e(isset($params['area_id']) ? $params['area_id'] : ''); ?>">
                <input type="hidden" name="status" value="<?php echo e(isset($params['status']) ? $params['status'] : ''); ?>">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                    <?php echo app('translator')->get('Export người dùng'); ?></button>
            </form>
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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('keyword_note'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Role'); ?></label>
                                <select name="role" id="roles" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['role']) && $item->id == $params['role'] ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Admin type'); ?></label>
                                <select name="admin_type" id="admin_type" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $admin_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['admin_type']) && $val == $params['admin_type'] ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val); ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" id="area" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $val->id == $params['area_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($val->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select name="department_id" id="department_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['department_id']) && $val->id == $params['department_id'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($val->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?></label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $key == $params['status'] ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
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
            <div class="box-body">
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

                <?php if(!$admins->total()): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('No record found on the system!'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã</th>
                                <th><?php echo app('translator')->get('Full name'); ?></th>
                                <th><?php echo app('translator')->get('Email/SĐT'); ?></th>
                                <th><?php echo app('translator')->get('Thuộc khu vực'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực được quản lý'); ?></th>
                                <th><?php echo app('translator')->get('Phòng ban'); ?></th>
                                <th><?php echo app('translator')->get('Admin type'); ?></th>
                                <th><?php echo app('translator')->get('Role'); ?></th>
                                <th><?php echo app('translator')->get('Direct manager'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $admin->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>
                                        <td>
                                            <?php echo e($admin->admin_code); ?>

                                        </td>
                                        <td>
                                            <?php echo e($admin->name); ?>

                                        </td>
                                        <td>
                                            <?php echo e($admin->email); ?>

                                            <?php echo e($admin->phone != '' ? ' / ' . $admin->phone : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($admin->area->name ?? ''); ?>

                                        </td>

                                        <td>
                                            <?php if(isset($admin->list_area)): ?>
                                                <ul>
                                                    <?php $__currentLoopData = $admin->list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($i->name); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($admin->department->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo app('translator')->get($admin->admin_type); ?>
                                        </td>
                                        <td>
                                            <ul>
                                                <li><?php echo e($admin->role_name); ?></li>
                                                <?php if(isset($admin->list_role)): ?>
                                                    <?php $__currentLoopData = $admin->list_role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($i->name); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <?php echo e($admin->direct_manager->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo app('translator')->get($admin->status); ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Edit'); ?>" data-original-title="<?php echo app('translator')->get('Edit'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.edit', $admin->id)); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <?php if($admins->hasPages()): ?>
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-sm-5">
                            Tìm thấy <?php echo e($admins->total()); ?> kết quả
                        </div>
                        <div class="col-sm-7">
                            <?php echo e($admins->withQueryString()->links('admin.pagination.default')); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\dwn\resources\views/admin/pages/admins/index.blade.php ENDPATH**/ ?>