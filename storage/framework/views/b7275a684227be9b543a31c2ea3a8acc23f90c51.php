<!DOCTYPE html>
<html lang="<?php echo e($locale ?? 'vi'); ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo e($meta['seo_title']); ?>

    </title>
    <link rel="icon" href="<?php echo e($setting->favicon ?? ''); ?>" type="image/x-icon">
    
    <meta name="description" content="<?php echo e($meta['seo_description']); ?>" />
    <meta name="keywords" content="<?php echo e($meta['seo_keyword']); ?>" />
    <meta name="news_keywords" content="<?php echo e($meta['seo_keyword']); ?>" />
    <meta property="og:image" content="<?php echo e(env('APP_URL') . $meta['seo_image']); ?>" />
    <meta property="og:title" content="<?php echo e($meta['seo_title']); ?>" />
    <meta property="og:description" content="<?php echo e($meta['seo_description']); ?>" />
    <meta property="og:url" content="<?php echo e(Request::fullUrl()); ?>" />
    
    
    <?php echo $__env->make('frontend.panels.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->yieldPushContent('style'); ?>
    <style>
        .default-header {
            position: relative;
            background: #7C32FF;
        }

        .default-header.header-scrolled {
            position: fixed;
        }

        .breadcrumb a {
            color: inherit;
        }

        .sidebar ul li {
            padding: 10px 0px
        }

        .sidebar ul li a {
            color: initial
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            bottom: -3px;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .eye-icon {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <?php if(\View::exists('frontend.widgets.header.default')): ?>
        <?php echo $__env->make('frontend.widgets.header.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php else: ?>
        <?php echo e('View: frontend.widgets.header.default  do not exists!'); ?>

    <?php endif; ?>
    <div class="account">
        <div class="container">
            <?php if(session('errorMessage')): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo session('errorMessage'); ?>

                </div>
            <?php endif; ?>
            <?php if(session('successMessage')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo session('successMessage'); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            <?php endif; ?>

            <div class="row">
                <!-- News Posts -->
                <div class="col-lg-8 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user"></i> <?php echo app('translator')->get('Thông tin học sinh'); ?>
                                <button class="btn btn-sm btn-info pull-right btn_update">
                                    <i class="fa fa-edit"></i>
                                    <?php echo app('translator')->get('Cập nhật'); ?>
                                </button>
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>
                                        <strong><?php echo app('translator')->get('Họ và tên'); ?>: </strong>
                                        <?php echo e($detail->first_name ?? ''); ?>

                                        <?php echo e($detail->last_name ?? ''); ?>

                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong><?php echo app('translator')->get('CCCD'); ?>:
                                        </strong><?php echo e($detail->json_params->cccd ?? 'Chưa cập nhật'); ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong><?php echo app('translator')->get('Ngày sinh'); ?>:
                                        </strong><?php echo e($detail->birthday != '' ? date('d/m/Y', strtotime($detail->birthday)) : 'Chưa cập nhật'); ?>

                                    </p>
                                </div>

                                <div class="col-sm-6">
                                    <p><strong><?php echo app('translator')->get('SĐT'); ?>: </strong><?php echo e($detail->phone ?? 'Chưa cập nhật'); ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong><?php echo app('translator')->get('Email'); ?>: </strong><?php echo e($detail->email ?? ''); ?></p>
                                </div>

                                <div class="col-sm-6">
                                    <p><strong><?php echo app('translator')->get('Mã học viên'); ?>: </strong><?php echo e($detail->admin_code ?? ''); ?></p>
                                </div>

                                <div class="col-sm-12">
                                    <p><strong><?php echo app('translator')->get('Địa chỉ'); ?>:
                                        </strong><?php echo e($detail->json_params->address ?? 'Chưa cập nhật'); ?></p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-8 update_information" style="display: none">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user"></i> <?php echo app('translator')->get('Cập nhật thông tin'); ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <form action="<?php echo e(route('frontend.update.account')); ?>" method="post" class="form-update"
                                name="form-account-update">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Họ và tên'); ?> <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="<?php echo app('translator')->get('Họ và tên'); ?>" value="<?php echo e($detail->name ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Ngày sinh'); ?> <small class="text-red">*</small></label>
                                            <input type="date" class="form-control" name="birthday"
                                                placeholder="<?php echo app('translator')->get('Ngày sinh'); ?>"
                                                value="<?php echo e($detail->birthday != '' ? date('Y-m-d', strtotime($detail->birthday)) : null); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('SĐT'); ?> <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="<?php echo app('translator')->get('SĐT'); ?>" value="<?php echo e($detail->phone ?? ''); ?>"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Giới tính'); ?> <small class="text-red">*</small></label>
                                            <select name="gender" class="form-control">
                                                <option value="" disabled><?php echo app('translator')->get('Chọn giới tính'); ?></option>
                                                <?php $__currentLoopData = $gender; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($key); ?>"
                                                        <?php echo e($detail->gender == $val ? 'selected' : ''); ?>>
                                                        <?php echo e(__($val)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo app('translator')->get('Địa chỉ'); ?></label>
                                            <textarea rows="3" class="form-control" name="address"><?php echo e($detail->json_params->address ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group password-wrapper">
                                            <label><?php echo app('translator')->get('Mật khẩu'); ?> <small class="text-muted"><i>(Bỏ qua nếu bạn
                                                        không
                                                        muốn đổi mật khẩu)</i></small></label>
                                            <input class="form-control single-input" type="password" name="password"
                                                value="" autocomplete="off">
                                            <span class="toggle-password">
                                                <i class="eye-icon fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button"
                                            class="btn btn-danger btn-sm text-white text-uppercase btn_cancel">
                                            Hủy</button>
                                        <button type="submit"
                                            class="btn btn-success btn-sm text-white text-uppercase">
                                            <i class="fa fa-save"></i> Lưu thông tin</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <?php echo $__env->make('frontend.components.sticky.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
    <script>
        $('.btn_update').click(function() {
            $('.update_information').show();
            $('.information').hide();
        })
        $('.btn_cancel').click(function() {
            $('.update_information').hide();
            $('.information').show();
        })
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/frontend/pages/user/account.blade.php ENDPATH**/ ?>