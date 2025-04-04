

<?php $__env->startSection('content'); ?>
    <div class="login-box">
        <div class="login-logo">
            <b>Administrator</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="<?php echo e(route('admin.forgot.post')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>Alert!</h4>
                        <?php echo e(session('errorMessage')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('successMessage')); ?>

                    </div>
                <?php endif; ?>

                <div class="card text-center">
                    <div class="card-body px-5">
                        <p class="card-text py-2">
                            <?php echo app('translator')->get("Enter your email address and we'll send you an email with instructions to reset your password."); ?>
                        </p>
                        <div class="form-group">
                            <input type="email" id="typeEmail" required name="email" class="form-control my-3"
                                placeholder="<?php echo app('translator')->get('Email input'); ?>" />
                            <?php if($errors->has('email')): ?>
                                <span class="help-block">
                                    <?php echo e($errors->first('email')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block btn-flat" value="<?php echo app('translator')->get('Reset password'); ?>">
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <a class="" href="<?php echo e(route('admin.login')); ?>"><?php echo app('translator')->get('Login'); ?></a>
                        </div>
                    </div>
                </div>
                <?php
                    $referer = request()->headers->get('referer');
                ?>
                <input type="hidden" name="url" value="<?php echo e($referer); ?>">
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/forgot.blade.php ENDPATH**/ ?>