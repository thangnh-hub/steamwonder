

<?php $__env->startSection('content'); ?>
  <div class="container" style="max-width:80%;margin:auto;background:#FBFBFB;padding:20px">
    <h1><?php echo app('translator')->get('Forget Password Email'); ?></h1>

    <p><?php echo app('translator')->get('You can reset password from bellow link:'); ?></p>

    <a href="<?php echo e(route('frontend.password.reset.get', $token)); ?>"><?php echo app('translator')->get('Reset Password'); ?></a>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/emails/forget_password_frontend.blade.php ENDPATH**/ ?>