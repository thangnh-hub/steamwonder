<?php if($block): ?>
    <?php
        $title = $block->json_params->title->{$locale} ?? $block->title;
        $brief = $block->json_params->brief->{$locale} ?? $block->brief;
        $des = $block->json_params->des->{$locale} ?? '';
        $content = $block->json_params->content->{$locale} ?? $block->content;
        $image = $block->image != '' ? $block->image : null;
        $image_background = $block->image_background != '' ? $block->image_background : null;
        // Filter all blocks by parent_id
        $block_childs = $blocks->filter(function ($item, $key) use ($block) {
            return $item->parent_id == $block->id;
        });
    ?>

    <?php if($image_background != null): ?>
        <style>
            .home-banner-area {
                background: url(<?php echo e($image_background); ?>) no-repeat;
                background-size: cover;
                background-position: center center;
            }
        </style>
    <?php endif; ?>
    <div class="home">
        <div class="home_slider_container">
            <!-- Home Slider -->
            <div class="owl-carousel owl-theme home_slider">
                <?php if($block_childs): ?>
                    <?php $__currentLoopData = $block_childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $title_childs = $item->json_params->title->{$locale} ?? $item->title;
                            $brief_childs = $item->json_params->brief->{$locale} ?? $item->brief;
                            $des_childs = $item->json_params->des->{$locale} ?? '';
                            $content_childs = $item->json_params->content->{$locale} ?? $item->content;
                            $image_childs = $item->image != '' ? $item->image : null;
                            $image_background_childs = $item->image_background != '' ? $item->image_background : null;
                            $url_link_childs = $item->url_link != '' ? $item->url_link : '';
                            $url_link_title_childs =
                                $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
                            $icon_childs = $item->icon ?? '';
                        ?>
                        <div class="owl-item">
                            <div class="home_slider_background"
                                style="background-image:url(<?php echo e($image_background_childs); ?>)"></div>
                            <div class="home_container">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="home_content">
                                                <div class="home_text">
                                                    <div class="home_title"><?php echo e($title_childs); ?></div>
                                                    <div class="home_subtitle"><?php echo e($brief_childs); ?></div>
                                                </div>
                                                <div class="home_buttons">
                                                    <?php if($url_link_childs != '' && $url_link_title_childs != ''): ?>
                                                        <div class="button home_button"><a
                                                                href="<?php echo e($url_link_childs); ?>"><?php echo e($url_link_title_childs); ?>

                                                                <div class="button_arrow"><i class="fa fa-angle-right"
                                                                        aria-hidden="true"></i></div>
                                                            </a></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="home_slider_nav_container d-flex flex-row align-items-start justify-content-between">
                    <div class="home_slider_nav home_slider_prev trans_200"><i class="fa fa-angle-left"
                            aria-hidden="true"></i></div>
                    <div class="home_slider_nav home_slider_next trans_200"><i class="fa fa-angle-right"
                            aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/frontend/blocks/banner/layout/banner_home.blade.php ENDPATH**/ ?>