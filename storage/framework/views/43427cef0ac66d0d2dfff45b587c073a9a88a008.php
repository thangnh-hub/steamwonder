<!DOCTYPE html>
<html lang="<?php echo e($locale ?? 'vi'); ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo e($meta['seo_title']); ?>

    </title>
    <link rel="icon" href="<?php echo e($setting->favicon ?? ''); ?>" type="image/x-icon">
    
    <link rel="canonical" href="<?php echo e(Request::fullUrl()); ?>" />
    <meta name="description" content="<?php echo e($meta['seo_description']); ?>" />
    <meta name="keywords" content="<?php echo e($meta['seo_keyword']); ?>" />
    <meta name="news_keywords" content="<?php echo e($meta['seo_keyword']); ?>" />
    <meta property="og:image" content="<?php echo e(env('APP_URL') . $meta['seo_image']); ?>" />
    <meta property="og:title" content="<?php echo e($meta['seo_title']); ?>" />
    <meta property="og:description" content="<?php echo e($meta['seo_description']); ?>" />
    <meta property="og:url" content="<?php echo e(Request::fullUrl()); ?>" />
    
    
    <?php echo $__env->make('frontend.panels.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->yieldPushContent('style'); ?>

    <?php echo $__env->yieldPushContent('schema'); ?>
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }
        .panel{
            margin-top: 150px;
        }
    </style>
</head>

<body>
    <?php if(\View::exists('frontend.widgets.header.default')): ?>
        <?php echo $__env->make('frontend.widgets.header.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.header.default  do not exists!'); ?>

    <?php endif; ?>

    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center"><?php echo app('translator')->get('Forgot Password?'); ?></h2>
                            <p><?php echo app('translator')->get('You can reset your password here.'); ?></p>
                            <div class="panel-body">

                                <form id="register-form" action="<?php echo e(route('frontend.password.forgot.post')); ?>" role="form" autocomplete="off" class="form"
                                    method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="email address"
                                                class="form-control" type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block"
                                            value="Reset Password" type="submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(\View::exists('frontend.widgets.footer.default ')): ?>
        <?php echo $__env->make('frontend.widgets.footer.default ', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.footer.default do not exists!'); ?>

    <?php endif; ?>

    <?php echo $__env->make('frontend.components.sticky.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.panels.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.components.sticky.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    
    <?php echo $__env->yieldPushContent('script'); ?>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/pages/user/forgot_password.blade.php ENDPATH**/ ?>