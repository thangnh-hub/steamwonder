

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
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
                        <?php echo app('translator')->get('No record found on the system!'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Name'); ?></th>
                                <th><?php echo app('translator')->get('Link'); ?></th>
                                <th><?php echo app('translator')->get('Order'); ?></th>
                                <th><?php echo app('translator')->get('Updated at'); ?></th>
                                <th><?php echo app('translator')->get('Status'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($row->parent_id == 0 || $row->parent_id == null): ?>
                                    <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                                        onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                        <tr class="valign-middle">
                                            <td>
                                                <?php echo e(__($row->name)); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->url_link); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->iorder); ?>

                                            </td>
                                            <td>
                                                <?php echo e($row->updated_at); ?>

                                            </td>
                                            <td>
                                                <?php echo app('translator')->get($row->status); ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Edit'); ?>" data-original-title="<?php echo app('translator')->get('Edit'); ?>"
                                                    href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
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
                                    <?php if($row->submenu > 0): ?>
                                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($sub->parent_id == $row->id): ?>
                                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $sub->id)); ?>"
                                                    method="POST" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                                    <tr class="valign-middle bg-gray-light">
                                                        <td>
                                                            - - <?php echo e(__($sub->name)); ?>

                                                        </td>
                                                        <td>
                                                            - - <?php echo e($sub->url_link); ?>

                                                        </td>
                                                        <td>
                                                            - - <?php echo e($sub->iorder); ?>

                                                        </td>
                                                        <td>
                                                            <?php echo e($sub->updated_at); ?>

                                                        </td>
                                                        <td>
                                                            <?php echo app('translator')->get($sub->status); ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                                title="<?php echo app('translator')->get('Edit'); ?>"
                                                                data-original-title="<?php echo app('translator')->get('Edit'); ?>"
                                                                href="<?php echo e(route(Request::segment(2) . '.edit', $sub->id)); ?>">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button class="btn btn-sm btn-danger" type="submit"
                                                                data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                                data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </form>

                                                <?php if($sub->submenu > 0): ?>
                                                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($sub_2->parent_id == $sub->id): ?>
                                                            <form
                                                                action="<?php echo e(route(Request::segment(2) . '.destroy', $sub_2->id)); ?>"
                                                                method="POST"
                                                                onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                                                <tr class="valign-middle bg-gray-light">
                                                                    <td>
                                                                        - - - - <?php echo e(__($sub_2->name)); ?>

                                                                    </td>
                                                                    <td>
                                                                        - - - - <?php echo e($sub_2->url_link); ?>

                                                                    </td>
                                                                    <td>
                                                                        - - - - <?php echo e($sub_2->iorder); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($sub_2->updated_at); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo app('translator')->get($sub_2->status); ?>
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn btn-sm btn-warning"
                                                                            data-toggle="tooltip" title="<?php echo app('translator')->get('Edit'); ?>"
                                                                            data-original-title="<?php echo app('translator')->get('Edit'); ?>"
                                                                            href="<?php echo e(route(Request::segment(2) . '.edit', $sub_2->id)); ?>">
                                                                            <i class="fa fa-pencil-square-o"></i>
                                                                        </a>
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('DELETE'); ?>
                                                                        <button class="btn btn-sm btn-danger" type="submit"
                                                                            data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                                            data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\steamwonders\resources\views/admin/pages/admin_menus/index.blade.php ENDPATH**/ ?>