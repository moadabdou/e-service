<?php
function chooseUnitsFormView($availableModules, $selectedModules, $errors, $info, $totalHours,$minHours,$maxHours) {
    ob_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($errors)) {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
    ?>

    <div class="container mt-5 px-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h2 class="fw-bold text-primary mb-2 mb-md-0">📚 Choisir mes modules</h2>

            <a href="/e-service/views/pages/professor/SelectedUnits.php" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="ti ti-clipboard-list"></i> Voir les modules déjà sélectionnés
            </a>
        </div>
    </div>

    <div class="container mt-5 px-2 px-md-3">
        <?php if ($info) : ?>
            <div class="alert alert-<?= htmlspecialchars($info['type']) ?>"><?= htmlspecialchars($info['msg']) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <label class="form-label" style="font-size: 1.2rem; font-weight: bold;">Modules disponibles :</label><br>

                <?php if (empty($availableModules)) : ?>
                    <p class="text-muted">Aucun module disponible pour votre département actuellement.</p>
                <?php else : ?>
                    <?php foreach ($availableModules as $module) : ?>
                        <div class="module-card mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="module-title" style="font-size: 1.1rem; font-weight: bold;">
                                    <?= htmlspecialchars($module['title']) ?> 
                                    <span class="text-muted">(<?= htmlspecialchars($module['volume_horaire']) ?>h)</span>
                                </div>
                                <button type="button" class="btn btn-info btn-sm m-2" data-bs-toggle="collapse" data-bs-target="#moduleDetails-<?= htmlspecialchars($module['id_module']) ?>" aria-expanded="false" aria-controls="moduleDetails-<?= htmlspecialchars($module['id_module']) ?>">
                                    <i class="ti ti-info-circle"></i> Plus d'infos
                                </button>
                            </div>

                            <div class="collapse" id="moduleDetails-<?= htmlspecialchars($module['id_module']) ?>">
                                <div class="card card-body">
                                    <p><strong>Description :</strong> <?= htmlspecialchars($module['description'] ?? 'Aucune description disponible.') ?></p>
                                    <p><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></p>
                                    <p><strong>Professeur :</strong> <?= htmlspecialchars($module['professor_name'] ?? 'Non attribué') ?></p>
                                    <p><strong>Volume Horaire :</strong> <?= htmlspecialchars($module['volume_horaire'] ?? 'Aucune') ?> heures</p>
                                </div>
                            </div>

                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="modules[]" 
                                    value="<?= htmlspecialchars($module['id_module']) ?>"
                                    <?= in_array($module['id_module'], array_column($selectedModules, 'id_module')) ? 'checked' : '' ?>
                                >
                                <label class="form-check-label">
                                    Sélectionner ce module
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (isset($errors['modules'])) : ?>
                    <div class="text-danger mt-2"><?= htmlspecialchars($errors['modules']) ?></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($availableModules)) : ?>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-primary px-5 py-3 m-4 shadow rounded-pill">
                        <i class="ti ti-checkbox px-1"></i> Valider mes choix
                    </button>
                </div>
            <?php endif; ?>
        </form>

        <?php
if ($totalHours > 0) :
    $alertClass = 'info';
    if ($totalHours < $minHours || $totalHours > $maxHours) {
        $alertClass = 'danger';
    } elseif ($totalHours >= $minHours && $totalHours <= $maxHours) {
        $alertClass = 'success';
    }
?>
            <div class="mt-5">
                <div class="alert alert-<?= $alertClass ?> text-center fw-semibold fs-5 shadow-sm">
                <i class="ti ti-clock"></i> Charge horaire totale sélectionnée : 
                    <strong><?= htmlspecialchars($totalHours) ?> heures</strong><br>

                    <?php if ($totalHours < $minHours): ?>
                        <small class="d-block text-danger">Le minimum requis est <?= htmlspecialchars($minHours) ?>h.</small>
                    <?php elseif ($totalHours > $maxHours): ?>
                        <small class="d-block text-danger">Le maximum autorisé est <?= htmlspecialchars($maxHours) ?>h.</small>
                    <?php else: ?>
                        <small class="d-block text-success">Charge horaire dans la plage autorisée ✅</small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}
?>
