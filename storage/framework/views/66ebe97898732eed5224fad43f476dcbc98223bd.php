<?php
    if (isset($menu)) {
        $menu_header = $menu->first(function ($item, $key) {
            return $item->menu_type == 'header';
        });
        $menu_childs = $menu->filter(function ($item, $key) use ($menu_header) {
            return $item->parent_id == $menu_header->id;
        });
    }
?>
<header class="header">
    <!-- Top Bar -->
    <div class="top_bar">
        <div class="top_bar_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="top_bar_content d-flex flex-row align-items-center justify-content-start">
                            <ul class="top_bar_contact_list">
                                <li>
                                    <div class="question">
                                        <?php echo e($locale == $lang_default ? $setting->slogan : $setting->{$locale . '-slogan'} ?? ''); ?>

                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <?php echo e($locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? ''); ?>

                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <?php echo e($locale == $lang_default ? $setting->email : $setting->{$locale . '-email'} ?? ''); ?>

                                    </div>
                                </li>
                            </ul>
                            <?php if(isset($user_auth)): ?>
                                <div class="top_bar_login ml-auto">
                                    <div class="button">
                                        <a href="<?php echo e(route('frontend.user')); ?>" class="text-white">
                                            <?php echo app('translator')->get('Thông tin tài khoản'); ?>
                                            <div class="button_arrow"><i class="fa fa-user-circle-o"
                                                    aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="top_bar_login ml-auto">
                                    <div class="button">
                                        <a href="<?php echo e(route('frontend.login')); ?>" class="text-white" data-toggle="modal"
                                            data-target="#loginModal">
                                            <?php echo app('translator')->get('Đăng nhập'); ?>
                                            <div class="button_arrow"><i class="fa fa-sign-in" aria-hidden="true"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Content -->
    <div class="header_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header_content d-flex flex-row align-items-center justify-content-start">
                        <div class="logo_container">
                            <a href="<?php echo e(route('home')); ?>">
                                <div class="logo_content d-flex flex-row align-items-end justify-content-start">
                                    <div class="logo_img"><img src="<?php echo e($setting->logo_header); ?>"
                                            alt="<?php echo e($setting->site_title); ?>"></div>
                                    
                                </div>
                            </a>
                        </div>

                        <nav class="main_nav_contaner ml-auto">
                            <ul class="main_nav">
                                <?php if(isset($menu_childs) && count($menu_childs) > 0): ?>
                                    <?php $__currentLoopData = $menu_childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_menu1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li
                                            class="<?php echo e((parse_url(url()->full(), PHP_URL_PATH) == '' && $val_menu1->url_link == '/') || $val_menu1->url_link == parse_url(url()->full(), PHP_URL_PATH) ? 'active' : ''); ?>">
                                            <a
                                                href="<?php echo e($val_menu1->url_link ?? 'javascript:void(0)'); ?>"><?php echo e($val_menu1->json_params->name->$locale ?? $val_menu1->name); ?></a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if(isset($user_auth)): ?>
                                    <li class="">
                                        <a href="<?php echo e(route('frontend.logout')); ?>"><?php echo app('translator')->get('Đăng xuất'); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            
                            <!-- Hamburger -->

                            <div class="hamburger menu_mm">
                                <i class="fa fa-bars menu_mm" aria-hidden="true"></i>
                            </div>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Search Panel -->
    

    <!-- Header Search Panel -->
    <div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
        <div class="menu_close_container">
            <div class="menu_close">
                <div></div>
                <div></div>
            </div>
        </div>
        
        <nav class="menu_nav">
            <ul class="menu_mm">
                <?php if(isset($menu_childs) && count($menu_childs) > 0): ?>

                    <?php $__currentLoopData = $menu_childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val_menu1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="menu_mm">
                            <a
                                href="<?php echo e($val_menu1->url_link ?? 'javascript:void(0)'); ?>"><?php echo e($val_menu1->json_params->name->$locale ?? $val_menu1->name); ?></a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <?php if(isset($user_auth)): ?>
                    <li class="">
                        <a href="<?php echo e(route('frontend.logout')); ?>"><?php echo app('translator')->get('Đăng xuất'); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="menu_extra">
            <div class="menu_phone"><span
                    class="menu_title"><?php echo app('translator')->get('Phone'); ?>:</span><?php echo e($locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? ''); ?>

            </div>
            <div class="menu_social">
                <span class="menu_title"><?php echo app('translator')->get('follow us'); ?></span>
                <ul>
                    <?php if(isset($setting->facebook_url) || isset($setting->{$locale . '-facebook_url'})): ?>
                        <li><a
                                href="<?php echo e($locale == $lang_default ? $setting->facebook_url : $setting->{$locale . '-facebook_url'} ?? ''); ?>"><i
                                    class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                    <?php if(isset($setting->youtube_url) || isset($setting->{$locale . '-youtube_url'})): ?>
                        <li><a
                                href="<?php echo e($locale == $lang_default ? $setting->youtube_url : $setting->{$locale . '-youtube_url'} ?? ''); ?>"><i
                                    class="fa fa-youtube" aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                    <?php if(isset($setting->linkedin_url) || isset($setting->{$locale . '-linkedin_url'})): ?>
                        <li><a
                                href="<?php echo e($locale == $lang_default ? $setting->linkedin_url : $setting->{$locale . '-linkedin_url'} ?? ''); ?>"><i
                                    class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                    <?php if(isset($setting->instagram_url) || isset($setting->{$locale . '-instagram_url'})): ?>
                        <li><a
                                href="<?php echo e($locale == $lang_default ? $setting->instagram_url : $setting->{$locale . '-instagram_url'} ?? ''); ?>"><i
                                    class="fa fa-instagram" aria-hidden="true"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/widgets/header/default.blade.php ENDPATH**/ ?>