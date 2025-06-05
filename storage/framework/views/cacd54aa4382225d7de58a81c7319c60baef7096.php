<!DOCTYPE html>
<html lang="<?php echo e($locale ?? 'vi'); ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style>
    * {
      font-size: 14px;
    }

    .container h1 {
      font-weight: 300;
      margin-top: 0;
      font-size: 24px;
    }

  </style>

  <?php echo $__env->yieldContent('style'); ?>

</head>

<body style="background:#fff;font-family:'Roboto';">
  <?php echo $__env->yieldContent('content'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/frontend/layouts/email.blade.php ENDPATH**/ ?>