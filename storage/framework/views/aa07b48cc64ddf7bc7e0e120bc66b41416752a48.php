<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4><?php echo app('translator')->get('Đăng nhập'); ?></h4>
                </div>
                <div class="d-flex flex-column text-center form-login active">
                    <form id="login_form" method="post" class="login" action="<?php echo e(route('frontend.login.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php
                            $referer = request()->headers->get('referer');
                            $current = url()->full();
                        ?>
                        <input type="hidden" name="referer" value="<?php echo e($referer); ?>">
                        <input type="hidden" name="current" value="<?php echo e($current); ?>">
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="<?php echo app('translator')->get('Username'); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="<?php echo app('translator')->get('Mật khẩu'); ?>"
                                required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block btn-round"><?php echo app('translator')->get('Đăng nhập'); ?></button>
                        <div class="form-group login_result d-none mt-3">
                            <div class="alert alert-warning" role="alert">
                                <?php echo app('translator')->get('Processing...'); ?>
                            </div>
                        </div>

                    </form>
                    <div class="text-center mt-3">
                        <a class="text-info" href="<?php echo e(route('frontend.password.forgot.get')); ?>"><?php echo app('translator')->get('Quên mật khẩu'); ?></a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


<div class="modal fade" id="registernModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-title text-center">
                    <h4><?php echo app('translator')->get('Đăng ký'); ?></h4>
                </div>
                <div class="d-flex flex-column text-center">
                    <form id="signup_form" method="post" class="login" action="<?php echo e(route('frontend.signup')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="<?php echo app('translator')->get('Họ và tên của bạn ... '); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="<?php echo app('translator')->get('Địa chỉ Email của bạn ... '); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="json_params[cccd]"
                                placeholder="<?php echo app('translator')->get('Nhập CCCD ... '); ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="<?php echo app('translator')->get('Mật khẩu'); ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="repassword"
                                placeholder="<?php echo app('translator')->get('Nhập lại mật khẩu'); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block btn-round"><?php echo app('translator')->get('Đăng ký'); ?></button>
                        <div class="form-group signup_result d-none mt-3">
                            <div class="alert alert-warning" role="alert">
                                <?php echo app('translator')->get('Processing...'); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <div class="signup-section"><?php echo app('translator')->get('Đã là thành viên?'); ?> <a href="javascript:void(0)" class="text-info"
                        data-toggle="modal" data-target="#loginModal" data-dismiss="modal"> <?php echo app('translator')->get('Đăng nhập'); ?></a>.
                </div>
            </div>
        </div>
    </div>
</div>


<section class="navbar-size">
    <div class="navbar-size-wrapper">
        <div class="navbar-size-item <?php echo e(request()->server('REQUEST_URI') == '/' ? 'active' : ''); ?>">
            <a href="<?php echo e(route('home')); ?>">
                <div class="icon">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </div>
                <h2 class="text"><?php echo app('translator')->get('Trang chủ'); ?></h2>
            </a>
        </div>
        <?php if(isset($user_auth)): ?>
            <div class="navbar-size-item">
                <a href="<?php echo e(route('frontend.user.course')); ?>">
                    <div class="icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Khóa học</h2>
                </a>
            </div>
            <div class="navbar-size-item">
                <a href="<?php echo e(route('frontend.user')); ?>">
                    <div class="icon">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Tài khoản</h2>
                </a>
            </div>
        <?php else: ?>
            <div class="navbar-size-item">
                <a href="<?php echo e(route('frontend.course.list')); ?>">
                    <div class="icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Khóa học</h2>
                </a>
            </div>
            <div class="navbar-size-item">
                <a href="#loginModal" data-toggle="modal">
                    <div class="icon">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                    </div>
                    <h2 class="text">Đăng nhập</h2>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php /**PATH C:\laragon\www\steamwonder\resources\views/frontend/components/sticky/modal.blade.php ENDPATH**/ ?>