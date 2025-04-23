<header class="main-header">
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="<?php echo e(route('admin.home')); ?>" class="navbar-brand">
                    <i class="fa fa-home"></i>
                    <b class="hidden-xs">SteamWonder</b>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php $__currentLoopData = $accessMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->parent_id == 0 || $item->parent_id == null): ?>
                            <?php
                                $check = 0;
                                if (Request::segment(2) == $item->url_link && $item->url_link != '') {
                                    $check++;
                                }
                                foreach ($accessMenus as $sub) {
                                    if ($sub->parent_id == $item->id && Request::segment(2) == $sub->url_link && $sub->url_link != '') {
                                        $check++;
                                    }
                                }
                            ?>
                            <?php if($item->submenu > 0): ?>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="<?php echo e($item->icon != '' ? $item->icon : 'fa fa-angle-right'); ?>"></i>
                                        <?php echo e(__($item->name)); ?>

                                        <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <?php $__currentLoopData = $accessMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($sub->parent_id == $item->id): ?>
                                                <?php if($sub->submenu > 0): ?>
                                                    <li class="dropdown sub <?php echo e(Request::segment(2) == $sub->url_link && $sub->url_link != '' ? 'active' : ''); ?>">
                                                        <a href="javascript:void(0)">
                                                            <i
                                                                class="<?php echo e($sub->icon != '' ? $sub->icon : 'fa fa-angle-right'); ?>"></i>
                                                            <span><?php echo e(__($sub->name)); ?></span>
                                                            <i class="fa fa-angle-right pull-right" style="padding-top: 2px;"></i>
                                                        </a>

                                                        <ul class="dropdown-menu sub_child">
                                                            <?php $__currentLoopData = $accessMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if($sub_child->parent_id == $sub->id): ?>
                                                                    <li
                                                                        class="<?php echo e(Request::segment(2) == $sub_child->url_link && $sub_child->url_link != '' ? 'active' : ''); ?>">
                                                                        <a href="/admin/<?php echo e($sub_child->url_link); ?>">
                                                                            <i
                                                                                class="<?php echo e($sub_child->icon != '' ? $sub_child->icon : 'fa fa-angle-right'); ?>"></i>
                                                                            <span><?php echo e(__($sub_child->name)); ?></span>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>

                                                    </li>
                                                <?php else: ?>
                                                    <li
                                                        class="<?php echo e(Request::segment(2) == $sub->url_link && $sub->url_link != '' ? 'active' : ''); ?>">
                                                        <a href="/admin/<?php echo e($sub->url_link); ?>">
                                                            <i
                                                                class="<?php echo e($sub->icon != '' ? $sub->icon : 'fa fa-angle-right'); ?>"></i>
                                                            <span><?php echo e(__($sub->name)); ?></span>
                                                        </a>

                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="<?php echo e(Request::segment(2) == $item->url_link ? 'active' : ''); ?>">
                                    <a href="/admin/<?php echo e($item->url_link); ?>">
                                        <i class="<?php echo e($item->icon != '' ? $item->icon : 'fa fa-angle-right'); ?>"></i>
                                        <?php echo e(__($item->name)); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="toggleNotify()">
                            <?php echo app('translator')->get('Thông báo'); ?>
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger notify_read"><?php echo app('translator')->get('HOT'); ?></span>
                            
                        </a>
                        <ul class="dropdown-menu" id="toggle_notify" data-id ='1'>
                            <li>
                                <ul class="menu list_notify">
                                    
                                </ul>
                            </li>
                            <li class="footer"><a href="#" class="view_more_notify"><?php echo app('translator')->get('Xem thêm'); ?></a></li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span>
                                <?php echo e($admin_auth->name); ?>

                            </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <p>
                                    <?php echo e($admin_auth->name); ?>

                                    <small><?php echo e($admin_auth->email); ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo e(route('admin.account.change.get')); ?>"
                                        class="btn btn-default btn-flat"><?php echo app('translator')->get('Profile'); ?></a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo e(route('admin.logout')); ?>"
                                        class="btn btn-default btn-flat"><?php echo app('translator')->get('Logout'); ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/panels/header.blade.php ENDPATH**/ ?>