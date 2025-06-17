<!DOCTYPE html>
<html>

<head>
    <title>Google Drive</title>
</head>

<body>
    <form action="<?php echo e(route('google_drive.upload')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="file" name="file">
        <input type="text" name="content">
        <button type="submit">Upload</button>
    </form>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/google_drive/index.blade.php ENDPATH**/ ?>