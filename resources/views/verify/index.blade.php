@extends('layouts.app')

@section('title', 'Vérification QR Code')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Vérification QR Code</h1>
                <p class="text-gray-600">Scannez ou saisissez un code pour vérifier sa validité</p>
            </div>

            <!-- Scanner Section -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scanner le QR Code</label>
                    <div id="reader" class="mx-auto mb-4 w-full max-w-md"></div>
                    <button id="start-scanner"
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Démarrer le scanner
                    </button>
                    <button id="stop-scanner"
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition hidden mt-2">
                        Arrêter le scanner
                    </button>
                </div>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">OU</span>
                    </div>
                </div>

                <!-- Manual Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saisir le code manuellement</label>
                    <div class="flex gap-2">
                        <input type="text" id="code-input" placeholder="Entrez le code ici"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button id="verify-btn"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Vérifier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            <div id="result-section" class="hidden">
                <div id="result-card" class="bg-white rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 id="result-title" class="text-2xl font-bold"></h2>
                        <button id="close-result"
                            class="text-white text-2xl font-semibold leading-none hover:text-gray-200">
                            ✕
                        </button>
                    </div>
                    <div id="result-person-name" class="mb-3 hidden">
                        <p class="text-sm opacity-90 mb-1">Personne assignée:</p>
                        <p id="person-name-text" class="text-xl font-semibold"></p>
                    </div>
                    <p id="result-message" class="text-lg mb-4"></p>
                    <div id="result-actions" class="flex gap-3">
                        <button id="mark-used-btn"
                            class="bg-white text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition hidden">
                            Confirmer l'utilisation
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Scans -->
            <div id="recent-scans" class="mt-6 bg-white rounded-2xl shadow-xl p-6 hidden">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Scans récents</h3>
                <div id="scans-list" class="space-y-2"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let html5QrcodeScanner = null;
            let isScanning = false;

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Elements
            const startScannerBtn = document.getElementById('start-scanner');
            const stopScannerBtn = document.getElementById('stop-scanner');
            const verifyBtn = document.getElementById('verify-btn');
            const codeInput = document.getElementById('code-input');
            const resultSection = document.getElementById('result-section');
            const resultCard = document.getElementById('result-card');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');
            const resultPersonName = document.getElementById('result-person-name');
            const personNameText = document.getElementById('person-name-text');
            const resultActions = document.getElementById('result-actions');
            const markUsedBtn = document.getElementById('mark-used-btn');
            const closeResultBtn = document.getElementById('close-result');
            const recentScans = document.getElementById('recent-scans');
            const scansList = document.getElementById('scans-list');

            // Load recent scans from localStorage
            let recentScansData = JSON.parse(localStorage.getItem('recentScans') || '[]');
            if (recentScansData.length > 0) {
                recentScans.classList.remove('hidden');
                updateScansList();
            }

            // Start Scanner
            startScannerBtn.addEventListener('click', async () => {
                if (isScanning) return;

                try {
                    html5QrcodeScanner = new Html5Qrcode("reader");

                    await html5QrcodeScanner.start({
                            facingMode: "environment"
                        }, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        (decodedText) => {
                            handleScan(decodedText);
                        },
                        (errorMessage) => {
                            // Ignore errors
                        }
                    );

                    isScanning = true;
                    startScannerBtn.classList.add('hidden');
                    stopScannerBtn.classList.remove('hidden');
                } catch (err) {
                    alert('Erreur lors du démarrage du scanner: ' + err);
                }
            });

            // Stop Scanner
            stopScannerBtn.addEventListener('click', async () => {
                if (html5QrcodeScanner && isScanning) {
                    await html5QrcodeScanner.stop();
                    html5QrcodeScanner.clear();
                    isScanning = false;
                    startScannerBtn.classList.remove('hidden');
                    stopScannerBtn.classList.add('hidden');
                }
            });

            // Manual Verify
            verifyBtn.addEventListener('click', () => {
                const code = codeInput.value.trim().toUpperCase();
                if (code) {
                    verifyCode(code);
                }
            });

            codeInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const code = codeInput.value.trim().toUpperCase();
                    if (code) {
                        verifyCode(code);
                    }
                }
            });

            // Handle Scan
            function handleScan(code) {
                if (isScanning) {
                    stopScanner();
                }
                verifyCode(code);
            }

            // Verify Code
            async function verifyCode(code) {
                try {
                    const response = await fetch('{{ url('/verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            code
                        })
                    });

                    const data = await response.json();
                    showResult(data, code);

                    // Add to recent scans
                    addToRecentScans(code, data);
                } catch (error) {
                    showResult({
                        status: 'error',
                        message: 'Erreur de connexion'
                    }, code);
                }
            }

            // Show Result
            function showResult(data, code) {
                resultSection.classList.remove('hidden');

                const baseCardClasses = 'rounded-2xl shadow-xl p-6 text-white transition-all duration-300';
                const statusClasses = {
                    valid: 'bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-600',
                    used: 'bg-gradient-to-r from-amber-500 via-orange-500 to-orange-600',
                    unknown: 'bg-gradient-to-r from-rose-500 via-red-500 to-red-600',
                    error: 'bg-gradient-to-r from-rose-500 via-red-500 to-red-600'
                };

                resultCard.className = `${baseCardClasses} ${statusClasses[data.status] ?? statusClasses.error}`;

                // Afficher le nom de la personne si présent
                if (data.person_name) {
                    resultPersonName.classList.remove('hidden');
                    personNameText.textContent = data.person_name;
                } else {
                    resultPersonName.classList.add('hidden');
                }

                if (data.status === 'valid') {
                    resultTitle.textContent = '✓ Code Valide';
                    resultMessage.textContent = data.message;
                    markUsedBtn.classList.remove('hidden');
                    markUsedBtn.onclick = () => markAsUsed(code);
                } else if (data.status === 'used') {
                    resultTitle.textContent = '⚠ Code Déjà Utilisé';
                    resultMessage.textContent = data.message + (data.used_at ? ` (${data.used_at})` : '');
                    markUsedBtn.classList.add('hidden');
                } else {
                    resultTitle.textContent = '✗ Code Invalide';
                    resultMessage.textContent = data.message;
                    markUsedBtn.classList.add('hidden');
                }

                codeInput.value = '';
            }

            // Mark as Used
            async function markAsUsed(code) {
                try {
                    const response = await fetch('{{ url('/mark-used') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            code
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        showResult({
                            status: 'used',
                            message: 'Code marqué comme utilisé avec succès'
                        }, code);
                        markUsedBtn.classList.add('hidden');
                        updateRecentScans(code, {
                            status: 'used'
                        });
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    alert('Erreur lors de la confirmation');
                }
            }

            // Recent Scans
            function addToRecentScans(code, data) {
                const scan = {
                    code,
                    status: data.status,
                    timestamp: new Date().toLocaleString('fr-FR')
                };

                recentScansData.unshift(scan);
                if (recentScansData.length > 10) {
                    recentScansData = recentScansData.slice(0, 10);
                }

                localStorage.setItem('recentScans', JSON.stringify(recentScansData));
                recentScans.classList.remove('hidden');
                updateScansList();
            }

            function updateRecentScans(code, data) {
                const scan = recentScansData.find(s => s.code === code);
                if (scan) {
                    scan.status = data.status;
                    localStorage.setItem('recentScans', JSON.stringify(recentScansData));
                    updateScansList();
                }
            }

            function updateScansList() {
                scansList.innerHTML = recentScansData.map(scan => {
                    const statusClass = scan.status === 'valid' ? 'bg-green-100 text-green-800' :
                        scan.status === 'used' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800';
                    const statusText = scan.status === 'valid' ? 'Valide' :
                        scan.status === 'used' ? 'Utilisé' :
                        'Invalide';

                    return `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <span class="font-mono font-semibold">${scan.code}</span>
                    <span class="ml-3 px-2 py-1 rounded text-xs font-semibold ${statusClass}">${statusText}</span>
                </div>
                <span class="text-sm text-gray-500">${scan.timestamp}</span>
            </div>
        `;
                }).join('');
            }

            // Close Result
            closeResultBtn.addEventListener('click', () => {
                resultSection.classList.add('hidden');
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (html5QrcodeScanner && isScanning) {
                    html5QrcodeScanner.stop();
                }
            });
        </script>
    @endpush
@endsection
