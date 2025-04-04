<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo $__env->yieldContent('title'); ?> | DWN
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="<?php echo e(asset('themes/admin/img/kid-icon.png')); ?>">


    
    <?php echo $__env->make('admin.panels.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('style'); ?>
    <?php echo $__env->yieldPushContent('style'); ?>
</head>

<body class="skin-blue layout-top-nav">
    <div class="wrapper">

        
        <?php echo $__env->make('admin.panels.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            

            
            <?php echo $__env->yieldContent('content-header'); ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <!-- /.content-wrapper -->

        
        <?php echo $__env->make('admin.panels.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>
    <!-- ./wrapper -->

    
    <?php echo $__env->make('admin.panels.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('script'); ?>
    <?php echo $__env->yieldPushContent('script'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>