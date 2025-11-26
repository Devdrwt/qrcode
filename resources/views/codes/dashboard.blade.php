@extends('layouts.app')

@section('title', 'Tableau de bord - Codes QR')

@section('content')
@php
    $total = max($totalCodes, 1);
    $usedPercent = round(($usedCodes / $total) * 100);
    $availablePercent = round(($availableCodes / $total) * 100);
@endphp

<div class="container mx-auto px-4 py-16">
    <div class="max-w-6xl mx-auto space-y-12">
        <!-- KPI grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-orange-300 rounded-2xl shadow-xl p-6 text-gray-900">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold uppercase tracking-wide text-orange-800">Disponibles</span>
                    <span class="text-2xl">🟠</span>
                </div>
                <div class="text-4xl font-bold mb-2">{{ $availableCodes }}</div>
                <div class="h-2 bg-orange-100 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500" style="width: {{ $availablePercent }}%"></div>
                </div>
                <p class="text-xs text-orange-900 mt-2">{{ $availablePercent }}% du stock total</p>
            </div>
            <div class="bg-blue-200 rounded-2xl shadow-xl p-6 text-gray-900">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-blue-800 uppercase tracking-wide">Utilisés</span>
                    <span class="text-2xl">🔵</span>
                </div>
                <div class="text-4xl font-bold mb-2">{{ $usedCodes }}</div>
                <div class="h-2 bg-blue-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500" style="width: {{ $usedPercent }}%"></div>
                </div>
                <p class="text-xs text-blue-900 mt-2">{{ $usedPercent }}% déjà consommés</p>
            </div>
            <div class="bg-green-200 rounded-2xl shadow-xl p-6 text-gray-900">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-green-800 uppercase tracking-wide">Flux</span>
                    <span class="text-2xl">🟢</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-green-900 mb-1">Anonymes</p>
                        <div class="w-full h-2 bg-green-100 rounded-full">
                            <div class="h-full bg-green-500 w-3/4"></div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-green-900 mb-1">Nommés</p>
                        <div class="w-full h-2 bg-green-100 rounded-full">
                            <div class="h-full bg-green-600 w-2/4"></div>
                        </div>
                    </div>
                    <p class="text-xs text-green-900/70">Générez selon vos besoins, tout reste synchronisé ici.</p>
                </div>
            </div>
        </div>

        <!-- Navigation cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
            <a href="{{ route('codes.anonymous') }}"
                class="rounded-2xl p-6 text-gray-900 shadow-xl bg-orange-300 border border-orange-200 hover:-translate-y-1 transition">
                <div class="text-4xl mb-4">🧾</div>
                <h3 class="text-2xl font-bold mb-2">Générer des codes anonymes</h3>
                <p class="text-orange-900/90 mb-4">1 à 1000 QR codes d'un coup, parfait pour les invitations ouvertes.</p>
                <span class="font-semibold text-orange-900">Commencer →</span>
            </a>
            <a href="{{ route('codes.named') }}"
                class="rounded-2xl p-6 text-gray-900 shadow-xl bg-blue-200 border border-blue-100 hover:-translate-y-1 transition">
                <div class="text-4xl mb-4">👥</div>
                <h3 class="text-2xl font-bold mb-2">Créer des codes nommés</h3>
                <p class="text-blue-900/80 mb-4">Jusqu'à 10 invités par lot avec attribution directe.</p>
                <span class="font-semibold text-blue-900">Configurer →</span>
            </a>
            <a href="{{ route('verify.index') }}"
                class="rounded-2xl p-6 text-gray-900 shadow-xl bg-green-200 border border-green-100 hover:-translate-y-1 transition">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold mb-2">Vérifier des codes</h3>
                <p class="text-green-900/80 mb-4">Accédez immédiatement à l'interface de scan et de validation.</p>
                <span class="font-semibold text-green-900">Ouvrir le scanner →</span>
            </a>
        </div>
    </div>
</div>
@endsection


