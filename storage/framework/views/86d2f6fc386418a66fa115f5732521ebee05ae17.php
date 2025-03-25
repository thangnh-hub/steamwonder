<?php if(session('errorMessage')): ?>
    <script>
        Swal.fire({
            toast: true,
            icon: 'error',
            title: '<?php echo e(session('errorMessage')); ?>',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
<?php endif; ?>
<?php if(session('successMessage')): ?>
    <script>
        Swal.fire({
            toast: true,
            icon: 'success',
            title: '<?php echo e(session('successMessage')); ?>',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
<?php endif; ?>
<?php if(session('warningMessage')): ?>
    <script>
        Swal.fire({
            toast: true,
            icon: 'warning',
            title: '<?php echo e(session('warningMessage')); ?>',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
<?php endif; ?>
<?php if(session('successMessageCart')): ?>
    <script>
        Swal.fire({
            toast: true,
            icon: 'error',
            title: '<?php echo e(session('successMessageCart')); ?>',
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        })
    </script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script>
            Swal.fire({
                toast: true,
                icon: 'error',
                title: '<?php echo e($error); ?>',
                animation: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })
        </script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<div class="content_alert"></div>
<style>
    .alert-fixed {
        position: fixed;
        top: 0px;
        right: 0px;
        margin: 1rem;
        z-index: 999999;
    }
</style>
<?php /**PATH D:\project\dwn\resources\views/frontend/components/sticky/alert.blade.php ENDPATH**/ ?>