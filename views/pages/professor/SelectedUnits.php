<div class="container px-1 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-primary mb-2 mb-md-0"> <i class="ti ti-checklist"></i> Vos modules déjà sélectionnés</h2>
        <a href="/e-service/internal/members/professor/choose_units.php" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="ti ti-arrow-back-up"></i> Retour à la sélection
        </a>
    </div>
</div>

<div class="container px-4 px-5 mb-5">
    <?php if (!empty($selectedModules)) : ?>
        <?= createSearchFilterComponent(
            "Rechercher un module...",
            [
                "semester" => [
                    "label" => "Semestre",
                    "icon" => "ti-calendar",
                    "allLabel" => "Tous les semestres",
                    "options" => [
                        ["value" => "s1", "label" => "Semestre 1"],
                        ["value" => "s2", "label" => "Semestre 2"],
                        ["value" => "s3", "label" => "Semestre 3"],
                        ["value" => "s4", "label" => "Semestre 4"],
                        ["value" => "s5", "label" => "Semestre 5"],
                        ["value" => "s6", "label" => "Semestre 6"]
                    ]
                ],
                "filiere" => [
                    "label" => "Filière",
                    "icon" => "ti-book",
                    "allLabel" => "Toutes les filières",
                    "options" => array_map(function ($f) {
                        return [
                            "value" => strtolower(str_replace(' ', '_', $f['filiere_name'])),
                            "label" => $f['filiere_name']
                        ];
                    }, $filliers)
                ],
                "status" => [
                    "label" => "Statut",
                    "icon" => "ti-hourglass-empty",
                    "allLabel" => "Tous les statuts",
                    "options" => [
                        ["value" => "validated", "label" => "Validé"],
                        ["value" => "in_progress", "label" => "En attente"],
                        ["value" => "declined", "label" => "Refusé"]
                    ]
                ]
            ],

            "listContainer",
            "filterable-item",
            "itemCount"
        ); ?>
    <?php endif; ?>
    </i>
    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show text-center"><?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (empty($selectedModules)) : ?>
        <div class="alert alert-warning text-center">
            Vous n'avez sélectionné aucun module pour le moment.
        </div>
    <?php else : ?>
        <div class="row g-4" id="listContainer">
            <?php foreach ($selectedModules as $module) : ?>
                <div class="col-md-6 col-lg-4 filterable-item"
                    data-semester="<?= htmlspecialchars(strtolower($module['semester'] ?? '')) ?>"
                    data-filiere="<?= htmlspecialchars(strtolower(str_replace(' ', '_', $module['filiere_name'] ?? ''))) ?>"
                    data-status="<?= str_replace(' ', '_', strtolower($module['status'] ?? 'in_progress')) ?>">
                    <div class="card p-4 shadow-sm rounded-4 h-100">
                        <h5 class="card-title mb-2 text-primary fw-bold"><?= htmlspecialchars($module['title']) ?></h5>
                        <p class="mb-2"><strong>Volume horaire :</strong> <?= htmlspecialchars($module['volume_horaire']) ?> heures</p>
                        <p class="mb-2"><strong>Description :</strong><br><?= htmlspecialchars($module['description'] ?? 'Aucune description disponible.') ?></p>
                        <p><strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name'] ?? 'Aucune') ?></p>
                        <p class="mb-2"><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></p>
                        <div class="d-flex flex-column align-items-center mt-2 gap-2">
                            <?= getStatusBadge($module['status'] ?? 'in progress') ?>
                            <?php if (($module['status'] ?? '') !== 'validated') : ?>
                                <button 
                                    type="button" 
                                    class="btn btn-outline-danger btn-sm delete-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-module-id="<?= htmlspecialchars($module['id_module']) ?>"
                                    data-module-title="<?= htmlspecialchars($module['title']) ?>">
                                    <i class="ti ti-trash"></i> Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content shadow rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer le module <strong id="modalModuleTitle"></strong> ?</p>
                <input type="hidden" name="delete_module_id" id="modalModuleId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<script>
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const modalModuleId = document.getElementById('modalModuleId');
    const modalModuleTitle = document.getElementById('modalModuleTitle');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const moduleId = btn.getAttribute('data-module-id');
            const moduleTitle = btn.getAttribute('data-module-title');
            modalModuleId.value = moduleId;
            modalModuleTitle.textContent = moduleTitle;
        });
    });
</script>

<?php
$content = ob_get_clean();
?>