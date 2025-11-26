<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class VerifyController extends Controller
{
    /**
     * Afficher la page de vérification
     */
    public function index(): View
    {
        return view('verify.index');
    }

    /**
     * Vérifier un code (requête web)
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = Code::where('code', $request->code)->first();

        if (!$code) {
            return response()->json([
                'status' => 'unknown',
                'message' => 'Code invalide',
                'person_name' => null,
            ], 404);
        }

        if ($code->used) {
            return response()->json([
                'status' => 'used',
                'message' => 'Ce code a déjà été utilisé',
                'used_at' => $code->used_at?->format('d/m/Y H:i'),
                'person_name' => $code->person_name,
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'message' => 'Code valide',
            'person_name' => $code->person_name,
        ]);
    }

    /**
     * Marquer un code comme utilisé
     */
    public function markUsed(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = Code::where('code', $request->code)->first();

        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code invalide',
            ], 404);
        }

        if ($code->used) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce code a déjà été utilisé',
            ], 400);
        }

        $code->markAsUsed();

        return response()->json([
            'status' => 'success',
            'message' => 'Code marqué comme utilisé',
        ]);
    }

    /**
     * API: Vérifier un code
     */
    public function apiVerify(string $code): JsonResponse
    {
        $codeModel = Code::where('code', $code)->first();

        if (!$codeModel) {
            return response()->json([
                'status' => 'unknown',
                'message' => 'Code invalide',
                'person_name' => null,
            ], 404);
        }

        if ($codeModel->used) {
            return response()->json([
                'status' => 'used',
                'message' => 'Ce code a déjà été utilisé',
                'used_at' => $codeModel->used_at?->format('d/m/Y H:i'),
                'person_name' => $codeModel->person_name,
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'message' => 'Code valide',
            'person_name' => $codeModel->person_name,
        ]);
    }

    /**
     * API: Marquer un code comme utilisé
     */
    public function apiMarkUsed(string $code): JsonResponse
    {
        $codeModel = Code::where('code', $code)->first();

        if (!$codeModel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code invalide',
            ], 404);
        }

        if ($codeModel->used) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ce code a déjà été utilisé',
            ], 400);
        }

        $codeModel->markAsUsed();

        return response()->json([
            'status' => 'success',
            'message' => 'Code marqué comme utilisé',
        ]);
    }
}

