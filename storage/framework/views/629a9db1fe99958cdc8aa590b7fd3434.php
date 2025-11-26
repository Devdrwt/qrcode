<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Codes QR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .qr-code {
            width: 60px;
            height: 60px;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-used {
            background-color: #f59e0b;
            color: white;
        }
        .status-available {
            background-color: #10b981;
            color: white;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Codes QR</h1>
        <p>Total: <?php echo e($totalCodes); ?> codes | Généré le: <?php echo e($date); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">QR Code</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Date de création</th>
                <th>Date d'utilisation</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align: center;">
                    <?php if(file_exists($code->qr_path)): ?>
                        <img src="<?php echo e($code->qr_path); ?>" class="qr-code" alt="<?php echo e($code->code); ?>">
                    <?php else: ?>
                        <span style="color: #999;">N/A</span>
                    <?php endif; ?>
                </td>
                <td style="font-family: monospace; font-weight: bold;"><?php echo e($code->code); ?></td>
                <td><?php echo e($code->person_name ?? '-'); ?></td>
                <td>
                    <span class="status <?php echo e($code->used ? 'status-used' : 'status-available'); ?>">
                        <?php echo e($code->used ? 'Utilisé' : 'Disponible'); ?>

                    </span>
                </td>
                <td><?php echo e($code->created_at->format('d/m/Y H:i')); ?></td>
                <td><?php echo e($code->used_at ? $code->used_at->format('d/m/Y H:i') : '-'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Page {PAGENO} / {PAGECOUNT}</p>
    </div>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\Qr-code\resources\views/codes/pdf.blade.php ENDPATH**/ ?>