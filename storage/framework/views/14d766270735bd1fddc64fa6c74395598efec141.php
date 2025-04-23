<?php
    if (isset($menu)) {
        $menu_footer = $menu->filter(function ($item, $key) {
            return $item->menu_type == 'footer';
        });
    }
?>
<footer class="footer">
    <div class="container">
        <div class="row">

            <!-- About -->
            <div class="col-lg-3 footer_col">
                <div class="footer_about">
                    <div class="logo_container">
                        <a href="<?php echo e(route('home')); ?>">
                            <div class="logo_content d-flex flex-row align-items-end justify-content-start">
                                <div class="logo_img"><img src="<?php echo e($setting->logo_header); ?>" alt="<?php echo e($setting->site_title); ?>"></div>
                            </div>
                        </a>
                    </div>
                    <div class="footer_about_text">
                        <p><?php echo e($locale == $lang_default ? $setting->footer_text : $setting->{$locale . '-footer_text_url'} ?? ''); ?></p>
                    </div>
                    <div class="footer_social">
                        <ul>
                            <?php if(isset($setting->facebook_url) || isset($setting->{$locale . '-facebook_url'})): ?>
                                <li><a href="<?php echo e($locale == $lang_default ? $setting->facebook_url : $setting->{$locale . '-facebook_url'} ?? ''); ?>"
                                        rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                            <?php endif; ?>
                            <?php if(isset($setting->instagram_url) || isset($setting->{$locale . '-instagram_url'})): ?>
                                <li><a href="<?php echo e($locale == $lang_default ? $setting->instagram_url : $setting->{$locale . '-instagram_url'} ?? ''); ?>"
                                        rel="nofollow"><i class="fa fa-instagram"></i></a></li>
                            <?php endif; ?>
                            <?php if(isset($setting->linkedin_url) || isset($setting->{$locale . '-linkedin_url'})): ?>
                                <li><a href="<?php echo e($locale == $lang_default ? $setting->linkedin_url : $setting->{$locale . '-linkedin_url'} ?? ''); ?>"
                                        rel="nofollow"><i class="fa fa-linkedin"></i></a></li>
                            <?php endif; ?>
                            <?php if(isset($setting->youtube_url) || isset($setting->{$locale . '-youtube_url'})): ?>
                                <li><a href="<?php echo e($locale == $lang_default ? $setting->youtube_url : $setting->{$locale . '-youtube_url'} ?? ''); ?>"
                                        rel="nofollow"><i class="fa fa-youtube"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="copyright">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <?php echo e($locale == $lang_default ? $setting->copyright : $setting->{$locale . '-copyright'} ?? ''); ?>

                    </div>
                </div>
            </div>
            <?php if($menu_footer): ?>
                <?php $__currentLoopData = $menu_footer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $title = $item_menu->json_parrams->name->{$locale} ?? $item_menu->name;
                        $menu_childs = $menu->filter(function ($item, $key) use ($item_menu) {
                            return $item->parent_id == $item_menu->id;
                        });
                    ?>
                    <div class="col-lg-3 col-6 footer_col">
                        <div class="footer_links">
                            <div class="footer_title"><?php echo e($title); ?></div>
                            <ul class="footer_list">
                                <?php if($menu_childs): ?>
                                    <?php $__currentLoopData = $menu_childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $title_child =
                                                $item_child->json_parrams->name->{$locale} ?? $item_child->name;
                                            $url_link = $item_child->url_link ?? 'javascript:void(0)';
                                            $taget = $item_child->json_params->target ?? '';
                                        ?>
                                        <li><a href="<?php echo e($url_link); ?>"
                                                target="<?php echo e($taget); ?>"><?php echo e($title_child); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <div class="col-lg-3 footer_col">
                <div class="footer_contact">
                    <div class="footer_title"><?php echo app('translator')->get('Contact Us'); ?></div>
                    <div class="footer_contact_info">
                        <div class="footer_contact_item">
                            <div class="footer_contact_title"><?php echo app('translator')->get('Address'); ?>:</div>
                            <div class="footer_contact_line"><?php echo e($locale == $lang_default ? $setting->address : $setting->{$locale . '-address'} ?? ''); ?></div>
                        </div>
                        <div class="footer_contact_item">
                            <div class="footer_contact_title"><?php echo app('translator')->get('Phone'); ?>:</div>
                            <div class="footer_contact_line"><?php echo e($locale == $lang_default ? $setting->phone : $setting->{$locale . '-phone'} ?? ''); ?></div>
                        </div>
                        <div class="footer_contact_item">
                            <div class="footer_contact_title"><?php echo app('translator')->get('Email'); ?>:</div>
                            <div class="footer_contact_line"><?php echo e($locale == $lang_default ? $setting->email : $setting->{$locale . '-email'} ?? ''); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/frontend/widgets/footer/default.blade.php ENDPATH**/ ?>