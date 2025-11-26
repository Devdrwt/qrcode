<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

use Illuminate\Support\Carbon;

class CodeController extends Controller
{
    /**
     * Afficher le tableau de bord
     */
    public function dashboard()
    {
        $totalCodes = Code::query()->count();
        $usedCodes = Code::query()->where('used', true)->count();
        $availableCodes = Code::query()->where('used', false)->count();

        return view('codes.dashboard', [
            'totalCodes' => $totalCodes,
            'usedCodes' => $usedCodes,
            'availableCodes' => $availableCodes,
        ]);
    }

    /**
     * Afficher la page des codes anonymes
     */
    public function anonymous(Request $request)
    {
        $perPage = 20;
        $codes = Code::query()
            ->whereNull('person_name')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('codes.anonymous', [
            'codes' => $codes,
        ]);
    }

    /**
     * Afficher la page des codes nommés
     */
    public function named(Request $request)
    {
        $perPage = 20;
        $codes = Code::query()
            ->whereNotNull('person_name')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('codes.named', [
            'codes' => $codes,
        ]);
    }

    /**
     * Afficher la page de gestion des codes (ancienne méthode - redirection)
     */
    public function index(Request $request)
    {
        return redirect()->route('codes.dashboard');
    }

    /**
     * Générer des codes QR anonymes
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:1000',
        ]);

        $quantity = $validated['quantity'];
        $codes = [];

        $extension = 'png';

        for ($i = 0; $i < $quantity; $i++) {
            // Générer un code unique de 12 caractères alphanumériques
            do {
                /** @var string $code */
                $code = strtoupper(Str::random(12));
            } while (Code::where('code', $code)->exists());

            $codeModel = Code::create([
                'code' => $code,
                'person_name' => null,
                'used' => false,
            ]);

            // Générer le QR Code en PNG
            $qrCode = $this->buildPngQr($code);

            // Sauvegarder le QR Code
            $filename = "qrcodes/{$code}.{$extension}";
            StorageFacade::disk('public')->put($filename, $qrCode);

            $codes[] = [
                'id' => $codeModel->id,
                'code' => $code,
                'person_name' => null,
                'qr_url' => asset(StorageFacade::url($filename)),
            ];
        }

        return Response::json([
            'status' => 'success',
            'message' => "{$quantity} codes anonymes générés avec succès",
            'codes' => $codes,
        ]);
    }

    /**
     * Générer des codes QR nommés (max 10 personnes)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateNamed(Request $request)
    {
        $validated = $request->validate([
            'persons' => 'required|array|min:1|max:10',
            'persons.*' => 'required|string|max:255',
        ]);

        $persons = $validated['persons'];
        $codes = [];
        $extension = 'png';

        foreach ($persons as $personName) {
            $personName = trim($personName);
            if (empty($personName)) {
                continue;
            }

            // Générer un code unique de 12 caractères alphanumériques
            do {
                /** @var string $code */
                $code = strtoupper(Str::random(12));
            } while (Code::where('code', $code)->exists());

            $codeModel = Code::create([
                'code' => $code,
                'person_name' => $personName,
                'used' => false,
            ]);

            // Générer le QR Code en PNG
            $qrCode = $this->buildPngQr($code);

            // Sauvegarder le QR Code
            $filename = "qrcodes/{$code}.{$extension}";
            StorageFacade::disk('public')->put($filename, $qrCode);

            $codes[] = [
                'id' => $codeModel->id,
                'code' => $code,
                'person_name' => $personName,
                'qr_url' => asset(StorageFacade::url($filename)),
            ];
        }

        return Response::json([
            'status' => 'success',
            'message' => count($codes) . " codes nommés générés avec succès",
            'codes' => $codes,
        ]);
    }

    /**
     * Afficher un QR Code
     */
    public function showQR(string $code)
    {
        $codeModel = Code::query()->where('code', $code)->firstOrFail();

        $filename = "qrcodes/{$code}.png";

        if (!StorageFacade::disk('public')->exists($filename)) {
            // Générer le QR Code s'il n'existe pas
            $qrCode = $this->buildPngQr($code);

            StorageFacade::disk('public')->put($filename, $qrCode);
        }

        return StorageFacade::disk('public')->response($filename);
    }

    /**
     * Exporter les codes (CSV)
     */
    public function export()
    {
        $codes = Code::query()->orderBy('created_at', 'desc')->get();

        $filename = 'codes_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($codes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Code', 'Nom', 'Statut', 'Date de création', 'Date d\'utilisation']);

            foreach ($codes as $code) {
                fputcsv($file, [
                    $code->code,
                    $code->person_name ?? '-',
                    $code->used ? 'Utilisé' : 'Disponible',
                    $code->created_at->format('d/m/Y H:i'),
                    $code->used_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Exporter les codes en PDF
     */
    public function exportPdf()
    {
        $codes = Code::query()->orderBy('created_at', 'desc')->get();

        // Les attributs qr_url et qr_path sont gérés par le modèle Code

        $pdf = Pdf::loadView('codes.pdf', [
            'codes' => $codes,
            'totalCodes' => $codes->count(),
            'date' => Carbon::now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('codes_qr_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Générer une image PNG du QR code via GD (sans Imagick).
     */
    protected function buildPngQr(string $code): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return $result->getString();
    }
}
