<div class="col-lg-4 my-account">
    <div class="sidebar sticky-sidebar">
        <div class="profile">
            <div class="body">
                <div class="title">
                    <span class="user-name">
                        <?php echo e($detail->first_name ?? ''); ?>

                        <?php echo e($detail->last_name ?? ''); ?>

                    </span>
                </div>
            </div>
        </div>

        <div class="sidebar_links my-0 px-0 py-0">
            <ul>
                <li class="<?php echo e(url()->current() == route('frontend.user') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('frontend.user')); ?>">
                        <i class="fa fa-user mr-1" aria-hidden="true"></i>
                        <?php echo app('translator')->get('Thông tin cá nhân'); ?>
                    </a>
                </li>
                <li class="<?php echo e(url()->current() == route('frontend.user.course') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('frontend.user.course')); ?>">
                        <i class="fa fa-graduation-cap mr-1" aria-hidden="true"></i>
                        <?php echo app('translator')->get('Khóa học đã đăng ký'); ?>
                    </a>
                </li>
                <li class="<?php echo e(url()->current() == '' ? 'active' : ''); ?>">
                    <a href="#">
                        <i class="fa fa-list-alt mr-1" aria-hidden="true"></i>
                        <?php echo app('translator')->get('Báo cáo học tập'); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('frontend.logout')); ?>">
                        <i class="fa fa-sign-out mr-1" aria-hidden="true"></i>
                        <?php echo app('translator')->get('Đăng xuất'); ?>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/components/sticky/sidebar.blade.php ENDPATH**/ ?>