@extends('layouts.app')

@section('title', 'Gestion des Codes QR')

@section('content')
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-10">
                <p
                    class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/70 text-sm font-semibold text-indigo-600">
                    Nouvelle interface
                </p>
                <h1 class="text-4xl font-bold text-gray-900 mt-4 mb-3">Gestion centralisée des codes QR</h1>
                <p class="text-lg text-gray-600">La page historique a été remplacée par un tableau de bord moderne. Utilisez
                    les
                    boutons ci-dessous pour accéder aux nouvelles sections.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <a href="{{ route('codes.dashboard') }}"
                    class="block bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-xl transition">
                    <div class="text-4xl mb-4">📊</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tableau de bord</h2>
                    <p class="text-gray-600">Statistiques globales, accès aux exports et navigation vers les générateurs.
                    </p>
                </a>
                <a href="{{ route('codes.anonymous') }}"
                    class="block bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-xl transition">
                    <div class="text-4xl mb-4">🧾</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Codes Anonymes</h2>
                    <p class="text-gray-600">Générez entre 1 et 1000 codes anonymes avec un design moderne.</p>
                </a>
                <a href="{{ route('codes.named') }}"
                    class="block bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:-translate-y-1 hover:shadow-xl transition md:col-span-2">
                    <div class="text-4xl mb-4">👥</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Codes Nommés</h2>
                    <p class="text-gray-600">Créez jusqu'à 10 codes personnalisés pour des invités identifiables.</p>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <h3 class="text-3xl font-bold text-gray-900 mb-6">Créez vos codes QR en trois étapes simples</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 text-white flex items-center justify-center text-2xl">
                            1
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-gray-900">Choisissez le type</h4>
                        <p class="text-gray-600 text-sm">Anonyme pour la distribution de masse ou nommé pour une invitation
                            personnalisée.</p>
                    </div>
                    <div class="p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 text-white flex items-center justify-center text-2xl">
                            2
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-gray-900">Personnalisez</h4>
                        <p class="text-gray-600 text-sm">Définissez la quantité ou la liste des noms, puis déclenchez la
                            génération.</p>
                    </div>
                    <div class="p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-400 to-sky-500 text-white flex items-center justify-center text-2xl">
                            3
                        </div>
                        <h4 class="text-xl font-semibold mb-2 text-gray-900">Téléchargez</h4>
                        <p class="text-gray-600 text-sm">Récupérez vos QR codes en PNG et utilisez-les en impression ou en
                            version numérique.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
