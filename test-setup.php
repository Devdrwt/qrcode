<?php

/**
 * Script de test pour générer quelques codes QR
 * Exécutez: php test-setup.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Code;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

echo "=== Génération de codes de test ===\n\n";

// Créer le dossier qrcodes s'il n'existe pas
if (!Storage::disk('public')->exists('qrcodes')) {
    Storage::disk('public')->makeDirectory('qrcodes');
}

// Générer 5 codes de test
$testCodes = [];
for ($i = 0; $i < 5; $i++) {
    do {
        $code = strtoupper(Str::random(12));
    } while (Code::where('code', $code)->exists());

    $codeModel = Code::create([
        'code' => $code,
        'used' => false,
    ]);

    // Générer le QR Code en SVG
    $qrCode = QrCode::format('svg')
        ->size(300)
        ->generate($code);

    // Sauvegarder le QR Code
    $filename = "qrcodes/{$code}.svg";
    Storage::disk('public')->put($filename, $qrCode);

    $testCodes[] = $code;
    echo "✓ Code généré: {$code}\n";
}

echo "\n=== Codes de test générés ===\n";
echo "Vous pouvez maintenant tester avec ces codes:\n";
foreach ($testCodes as $code) {
    echo "- {$code}\n";
    echo "  QR Code: " . Storage::url("qrcodes/{$code}.svg") . "\n";
}

echo "\n=== Accès à l'application ===\n";
echo "Page de vérification: http://localhost/Qr-code/public/\n";
echo "Gestion des codes: http://localhost/Qr-code/public/admin/codes\n";
echo "\n";

