

<?php $__env->startSection('title', 'Codes Nommés'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="<?php echo e(route('codes.dashboard')); ?>" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4 font-medium transition">
                <span class="text-lg">←</span>
                <span>Retour au tableau de bord</span>
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Codes Nommés</h1>
                    <p class="text-gray-600">Génération personnalisée (maximum 10 personnes)</p>
                </div>
            </div>
        </div>

        <!-- Tools -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Exports globaux</h2>
                <p class="text-sm text-gray-500 mb-4">Téléchargez l'intégralité des codes générés.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('codes.export')); ?>"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                        📄 CSV
                    </a>
                    <a href="<?php echo e(route('codes.export-pdf')); ?>"
                        class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        📑 PDF
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Vérification rapide</h2>
                <p class="text-sm text-gray-500 mb-4">Accédez au scanner pour suivre l'utilisation des codes nominatifs.</p>
                <a href="<?php echo e(route('verify.index')); ?>"
                    class="inline-flex items-center gap-2 text-indigo-600 font-semibold text-sm hover:text-indigo-700 transition">🔍
                    Interface de vérification</a>
            </div>
        </div>

        <!-- Generate Section -->
        <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <span class="text-xl">👤</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Générer des codes nommés</h2>
                        <p class="text-violet-100 text-sm mt-1">Génération personnalisée (maximum 10 personnes)</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <label for="persons-input" class="block text-sm font-semibold text-gray-700 mb-2">
                        Liste des noms (un par ligne)
                    </label>
                    <textarea 
                        id="persons-input" 
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-500 shadow-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 resize-none font-mono text-sm bg-white"
                        placeholder="Jean Dupont&#10;Marie Martin&#10;Pierre Durand&#10;Sophie Bernard"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-2">Saisissez jusqu'à 10 noms, un par ligne. Chaque personne recevra un code QR unique.</p>
                </div>
                <button 
                    id="generate-named-btn" 
                    type="button"
                    class="w-full bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition shadow-md hover:shadow-lg transform hover:scale-[1.02]"
                >
                    Générer les codes nommés
                </button>
                <div id="generate-status" class="mt-4 hidden"></div>
            </div>
        </div>

        <!-- Codes Table -->
        <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">Liste des codes nommés</h2>
                    <span class="text-sm font-medium text-gray-600 bg-white px-3 py-1 rounded-lg">
                        <?php echo e($codes->firstItem() ?? 0); ?> - <?php echo e($codes->lastItem() ?? 0); ?> sur <?php echo e($codes->total()); ?>

                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">QR Code</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Code</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Nom</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Statut</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Date de création</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Date d'utilisation</th>
                            <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-3 text-center">
                                <img 
                                    src="<?php echo e($code->qr_url); ?>" 
                                    alt="<?php echo e($code->code); ?>" 
                                    class="w-14 h-14 object-contain mx-auto"
                                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\'%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EN/A%3C/text%3E%3C/svg%3E'"
                                >
                            </td>
                            <td class="border border-gray-300 px-4 py-3">
                                <span class="font-mono font-semibold text-gray-800"><?php echo e($code->code); ?></span>
                            </td>
                            <td class="border border-gray-300 px-4 py-3">
                                <span class="font-semibold text-violet-700"><?php echo e($code->person_name); ?></span>
                            </td>
                            <td class="border border-gray-300 px-4 py-3">
                                <?php if($code->used): ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                        Utilisé
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Disponible
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-600">
                                <?php echo e($code->created_at->format('d/m/Y H:i')); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-600">
                                <?php echo e($code->used_at ? $code->used_at->format('d/m/Y H:i') : '-'); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-3">
                                <a 
                                    href="<?php echo e($code->qr_url); ?>" 
                                    download="<?php echo e($code->code); ?>.png"
                                    class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-semibold hover:underline"
                                >
                                    <span>📥</span>
                                    <span>Télécharger</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                                Aucun code nommé généré pour le moment.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($codes->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($codes->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    const generateNamedBtn = document.getElementById('generate-named-btn');
    const personsInput = document.getElementById('persons-input');
    const generateStatus = document.getElementById('generate-status');

    if (generateNamedBtn && personsInput) {
        generateNamedBtn.addEventListener('click', async () => {
            const personsText = personsInput.value.trim();

            if (!personsText) {
                alert('Veuillez entrer au moins un nom');
                return;
            }

            const persons = personsText.split('\n')
                .map(line => line.trim())
                .filter(line => line.length > 0);

            if (persons.length === 0) {
                alert('Veuillez entrer au moins un nom valide');
                return;
            }

            if (persons.length > 10) {
                alert('Vous ne pouvez pas générer plus de 10 codes nommés à la fois');
                return;
            }

            generateNamedBtn.disabled = true;
            generateNamedBtn.textContent = 'Génération en cours...';
            generateStatus.classList.remove('hidden');
            generateStatus.innerHTML = '<div class="text-blue-600">Génération des codes nommés en cours...</div>';

            try {
                const response = await fetch('<?php echo e(route("codes.generate-named")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ persons })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    generateStatus.innerHTML = `<div class="text-green-600 font-semibold">${data.message}</div>`;
                    personsInput.value = '';
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    generateStatus.innerHTML = `<div class="text-red-600">Erreur: ${data.message}</div>`;
                }
            } catch (error) {
                generateStatus.innerHTML = '<div class="text-red-600">Erreur de connexion</div>';
            } finally {
                generateNamedBtn.disabled = false;
                generateNamedBtn.textContent = 'Générer les codes nommés';
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Qr-code\resources\views/codes/named.blade.php ENDPATH**/ ?>