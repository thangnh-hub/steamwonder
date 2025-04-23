

<?php $__env->startSection('title'); ?>
<?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo app('translator')->get($module_name); ?>
    <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2).'.create')); ?>"><i
        class="fa fa-plus"></i> <?php echo app('translator')->get('add_new'); ?></a>
  </h1>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title"><?php echo app('translator')->get('list'); ?></h3>
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
            <th><?php echo app('translator')->get('name'); ?></th>
            <th><?php echo app('translator')->get('module_code'); ?></th>
            <th><?php echo app('translator')->get('description'); ?></th>
            <th><?php echo app('translator')->get('iorder'); ?></th>
            <th><?php echo app('translator')->get('updated_at'); ?></th>
            <th><?php echo app('translator')->get('status'); ?></th>
            <th><?php echo app('translator')->get('action'); ?></th>
          </tr>
        </thead>
        <tbody>

            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

              <form action="<?php echo e(route(Request::segment(2).'.destroy', $row->id)); ?>" method="POST"
                onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                <tr class="valign-middle">
                  <td>
                    <?php echo e($row->name); ?>

                  </td>
                  <td>
                    <?php echo e($row->module_code); ?>

                  </td>
                  <td>
                    <?php echo e($row->description); ?>

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
                    <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo app('translator')->get('update'); ?>"
                      data-original-title="<?php echo app('translator')->get('update'); ?>" href="<?php echo e(route(Request::segment(2).'.edit', $row->id)); ?>">
                      <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="<?php echo app('translator')->get('delete'); ?>"
                      data-original-title="<?php echo app('translator')->get('delete'); ?>">
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

  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/modules/index.blade.php ENDPATH**/ ?>