

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <!-- Content Header (Page header) -->
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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo app('translator')->get('Update information user'); ?></h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="<?php echo e(route('admin.account.change.post')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('POST'); ?>
        <div class="box-body">

          <div class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold"><?php echo app('translator')->get('Role'); ?>:</label>
              <label class="col-sm-9 col-xs-12">
                    <?php echo e($admin_auth->getRole->name); ?>

              </label>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                <?php echo app('translator')->get('Fullname'); ?>:
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="text" id="name" class="form-control" name="name" required
                  value="<?php echo e(old('name') ?? $admin_auth->name); ?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                <?php echo app('translator')->get('Email'); ?>:
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="email" id="email" class="form-control" name="email" readonly
                  value="<?php echo e(old('email') ?? $admin_auth->email); ?>">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                <?php echo app('translator')->get('Password Old'); ?>:
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password_old" class="form-control" name="password_old" required value=""
                  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                <?php echo app('translator')->get('New Password'); ?>:
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password" class="form-control" name="password" required value=""
                  autocomplete="off">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 text-right text-bold">
                <?php echo app('translator')->get('Confirm New Password'); ?>:
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9 col-xs-12">
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required
                  value="" autocomplete="off">
              </div>
            </div>

          </div>

        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right btn-sm">
            <i class="fa fa-floppy-o"></i>
            <?php echo app('translator')->get('Save'); ?>
          </button>
        </div>
      </form>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\project\steamwonders\resources\views/admin/pages/admins/account.blade.php ENDPATH**/ ?>