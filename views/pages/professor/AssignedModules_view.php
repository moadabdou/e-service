<?php
function assignedModulesView(array $assignedModules, int $totalHours): string {
    ob_start();
?>

<div class="container mt-2 px-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-primary mb-2 mb-md-0">
            <i class="ti ti-books"></i> Modules Affectés Validés
        </h2>
    </div>

    <?php if (empty($assignedModules)) : ?>
        <div class="alert alert-warning text-center shadow-sm">
            <i class="ti ti-alert-circle"></i> Aucun module validé pour l'instant.
        </div>
    <?php else : ?>

        <div class="alert alert-success text-center fw-semibold fs-5 shadow rounded">
            <i class="ti ti-clock"></i> Charge horaire totale affectée :
            <strong><?= htmlspecialchars($totalHours) ?> heures</strong>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-1">
            <?php foreach ($assignedModules as $module) : ?>
                <div class="col">
                    <div class="card border-0 shadow h-100 overflow-hidden hover-shadow transition-300 mb-3">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-dark fw-bold d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($module['title']) ?>
                                    <span class="badge bg-primary-subtle text-primary fw-normal">
                                        <?= htmlspecialchars($module['volume_cours']) ?>h
                                    </span>
                                </h5>
                                <ul class="list-unstyled lh-lg">
                                    <li><strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name'] ?? 'Non spécifiée') ?></li>
                                    <li><strong>Description :</strong> <?= htmlspecialchars($module['description'] ?? 'Non disponible.') ?></li>
                                    <li><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></li>
                                    <li><strong>Volume horaire :</strong> <?= htmlspecialchars($module['volume_cours']) ?> heures</p></li>

                                </ul>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-end">
                            <span class="badge bg-success-subtle text-success">
                                <i class="ti ti-circle-check"></i> Validé
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>
<style>
    .transition-300 {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
    .icon-box {
        width: 24px;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .btn {
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s;
    }
</style>
<?php
    return ob_get_clean();
}
?>