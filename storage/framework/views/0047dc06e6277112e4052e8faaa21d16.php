<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'QR Code Verification'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\Qr-code\resources\views/layouts/app.blade.php ENDPATH**/ ?>