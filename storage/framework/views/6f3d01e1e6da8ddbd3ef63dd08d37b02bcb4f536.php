<!DOCTYPE html>
<html lang="<?php echo e($locale ?? 'vi'); ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
    </style>
</head>

<body>

    <?php if(\View::exists('frontend.widgets.header.default')): ?>
        <?php echo $__env->make('frontend.widgets.header.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.header.default  do not exists!'); ?>

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

    <?php if(\View::exists('frontend.widgets.footer.default ')): ?>
        <?php echo $__env->make('frontend.widgets.footer.default ', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.footer.default do not exists!'); ?>

    <?php endif; ?>
    <div class="modal fade" id="couserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h3 class="title"><?php echo e($title ?? ''); ?></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                        <div class="content">
                            <p class="font-weight-bold">Mức giá: <?php echo e($price ?? ''); ?> đ</p>
                            <p class="font-weight-bold">Thời gian: <?php echo e($thoi_luong ?? ''); ?></p>
                            <p class="font-weight-bold">Số bài học: <?php echo e($bai_hoc ?? ''); ?></p>
                        </div>
                        <div class="img ">
                            <img class="w-100" src="<?php echo e($image); ?>" alt="<?php echo e($title); ?>">
                        </div>

                    </div>
                    <div class="intro">
                        <h4><?php echo app('translator')->get('Nội dung khóa học'); ?></h4>
                        <div class="accordions">
                            <?php if(isset($detail->lessons)): ?>
                                <?php $__currentLoopData = $detail->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion_container">
                                        <div class="accordion d-flex flex-row align-items-center justify-content-between">
                                            <h4>
                                                <?php echo e(Str::limit($items->title, 50)); ?></h4>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="description mt-3">
                        <h4 class="mb-3"><?php echo app('translator')->get('Mô tả khóa học'); ?></h4>
                        <?php echo $brief ?? ''; ?>

                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <div class="button">
                            <a href="<?php echo e(route('frontend.order.courses', $detail->id)); ?>"
                                onclick="return confirm('Bạn chắc chắn muốn đăng ký khóa học này?');"><?php echo app('translator')->get('Đăng ký ngay'); ?>
                                <div class="button_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('frontend.components.sticky.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.panels.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.components.sticky.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    
    <?php echo $__env->yieldPushContent('script'); ?>

</body>

</html>
<?php /**PATH D:\project\dwn\resources\views/frontend/layouts/lesson.blade.php ENDPATH**/ ?>