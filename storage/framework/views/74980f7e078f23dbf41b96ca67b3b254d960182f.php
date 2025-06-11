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
</head>

<body>
    <?php if(\View::exists('frontend.widgets.header.default')): ?>
        <?php echo $__env->make('frontend.widgets.header.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.header.default  do not exists!'); ?>

    <?php endif; ?>

    <?php if(isset($user_auth)): ?>
        <?php if(isset($students)): ?>
            <div class="feature py-5">
                <div class="container">
                    <div class="row">
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="item col-6 col-lg-3">
                                <a href="<?php echo e(route('frontend.setSessionUser', $student->id)); ?>" class="btn btn <?php echo e(Session::get('user') == $student->id?'btn-success':'btn-light'); ?>">
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                    <?php echo e($student->first_name ?? ''); ?> <?php echo e($student->last_name ?? ''); ?>

                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <div class="container">
                    <div class="row">
                        <div class="item col-6 col-lg-3">
                            <a href="<?php echo e(route('frontend.user.student')); ?>"
                                class="btn btn <?php echo e(parse_url(route('frontend.user.student'), PHP_URL_PATH) == parse_url(url()->full(), PHP_URL_PATH) ? 'btn-success' : 'btn-light'); ?> ">
                                <i class="fa fa-address-card" aria-hidden="true"></i>
                                <?php echo app('translator')->get('Thông tin học sinh'); ?>
                            </a>
                        </div>
                        <div class="item col-6 col-lg-3">
                            <a href="<?php echo e(route('frontend.user.attendance')); ?>"
                                class="btn btn <?php echo e(parse_url(route('frontend.user.attendance'), PHP_URL_PATH) == parse_url(url()->full(), PHP_URL_PATH) ? 'btn-success' : 'btn-light'); ?> ">
                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                <?php echo app('translator')->get('Thông tin điểm danh'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(isset($blocks_selected)): ?>
        <?php $__currentLoopData = $blocks_selected; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\View::exists('frontend.blocks.' . $block->block_code . '.index')): ?>
                <?php echo $__env->make('frontend.blocks.' . $block->block_code . '.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php else: ?>
                <?php echo e('View: frontend.blocks.' . $block->block_code . '.index do not exists!'); ?>

            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php echo $__env->yieldContent('content'); ?>
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
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/layouts/default.blade.php ENDPATH**/ ?>