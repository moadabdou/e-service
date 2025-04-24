<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function pendingModulesView(array $pendingModules, array $filliers, ?array $info = null): string {
    ob_start();

    // Extraire les professeurs distincts pour le filtre
    $professorOptions = [];
    foreach ($pendingModules as $mod) {
        $label = $mod['firstName'] . ' ' . $mod['lastName'];
        $value = strtolower(str_replace(' ', '_', $label));
        if (!in_array(needle: $value, haystack: array_column(array: $professorOptions, column_key: 'value'))) {
            $professorOptions[] = [
                "value" => $value,
                "label" => $label
            ];
        }
    }
?>
    <div class="container mt-4 px-4 px-md-5">
        <!-- Page Header with improved styling -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
            <h2 class="fw-bold text-primary mb-0">
                <i class="ti ti-hourglass me-2"></i>Modules en attente de validation
            </h2>
            <div class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-pill">
                <span id="itemCount"><?= count($pendingModules) ?></span> module(s)
            </div>
        </div>

        <?php if ($info): ?>
            <div class="alert alert-<?= htmlspecialchars($info['type']) ?> alert-dismissible fade show shadow-sm border-start border-<?= htmlspecialchars($info['type']) ?> border-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-info-circle me-2 fs-5"></i>
                    <div><?= htmlspecialchars($info['msg']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($pendingModules)) : ?>
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
                                    ["value" => "s6", "label" => "Semestre 6"],
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
                            "professor" => [
                                "label" => "Professeur",
                                "icon" => "ti-user",
                                "allLabel" => "Tous les professeurs",
                                "options" => $professorOptions
                            ]
                        ],
                        "listContainer",
                        "filterable-item",
                        "itemCount"
                    ); ?>

        <?php endif; ?>

        <?php if (empty($pendingModules)) : ?>
            <div class="alert alert-warning text-center shadow-sm rounded-4 p-4">
                <i class="ti ti-alert-circle fs-5 d-block mb-3"></i>
                <h5>Aucun module en attente pour le moment</h5>
                <p class="text-muted mb-0">Les nouveaux modules à valider apparaîtront ici</p>
            </div>
        <?php else : ?>
            <div class="row g-4" id="listContainer">
                <?php foreach ($pendingModules as $module) :
                    $professorSlug = strtolower(str_replace(' ', '_', $module['firstName'] . ' ' . $module['lastName']));
                ?>
                    <div class="col-12 col-md-6 col-lg-4 filterable-item mb-4"
                        data-semester="<?= htmlspecialchars(strtolower($module['semester'])) ?>"
                        data-filiere="<?= htmlspecialchars(strtolower(str_replace(' ', '_', $module['filiere_name']))) ?>"
                        data-professor="<?= htmlspecialchars($professorSlug) ?>">
                        <div class="card h-100 shadow-sm rounded-4 border-0 hover-shadow transition-300 mb-2">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-primary fw-bold mb-0">
                                        <?= htmlspecialchars($module['module_title']) ?>
                                    </h5>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                        <?= htmlspecialchars($module['volume_horaire']) ?>h
                                    </span>
                                </div>
                            </div>
                            <div class="card-body px-4 pb-4 pt-2">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-user-circle text-primary me-2"></i>
                                        <span class="fw-medium"><?= htmlspecialchars($module['firstName'] . " " . $module['lastName']) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-mail text-secondary me-2"></i>
                                        <span class="text-muted small"><?= htmlspecialchars($module['email']) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-book text-secondary me-2"></i>
                                        <span class="text-muted small"><strong>Filière:</strong> <?= htmlspecialchars($module['filiere_name']) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-calendar text-secondary me-2"></i>
                                        <span class="text-muted small"><strong>Semestre:</strong> <?= formatSemester($module['semester']) ?></span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-medium mb-2">Description</h6>
                                    <p class="text-muted small mb-0">
                                        <?= !empty($module['description']) ? htmlspecialchars($module['description']) : '<em>Aucune description fournie</em>' ?>
                                    </p>
                                </div>
                                <form method="POST" class="module-form">
                                    <input type="hidden" name="module_id" value="<?= $module['id_module'] ?>">
                                    <input type="hidden" name="professor_id" value="<?= $module['by_professor'] ?>">
                                    <input type="hidden" name="action" value="">
                                    
                                    <div class="d-flex mt-4 pt-3 border-top">
                                        <div class="d-flex gap-2 w-100">
                                            <button type="button" class="btn btn-outline-success validate-btn flex-grow-1">
                                                <i class="ti ti-circle-check me-1"></i> Valider
                                            </button>
                                            <button type="button" class="btn btn-outline-danger decline-btn flex-grow-1">
                                                <i class="ti ti-circle-x me-1"></i> Refuser
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="/e-service/internal/members/common/view_profile.php?id=<?= $module['by_professor'] ?>" 
                                           class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                            <i class="ti ti-user-circle me-1"></i> Voir profil
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effect to cards
            document.querySelectorAll('.hover-shadow').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.classList.add('shadow');
                    card.classList.remove('shadow-sm');
                });
                card.addEventListener('mouseleave', () => {
                    card.classList.remove('shadow');
                    card.classList.add('shadow-sm');
                });
            });
            
            // Enhanced action handlers
            document.querySelectorAll(".module-form").forEach(form => {
                const validateBtn = form.querySelector(".validate-btn");
                const declineBtn = form.querySelector(".decline-btn");

                const handleAction = (action, title, message, confirmButtonColor, icon) => {
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmButtonColor,
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Oui, confirmer",
                        cancelButtonText: "Annuler",
                        buttonsStyling: true,
                        customClass: {
                            confirmButton: 'btn btn-lg',
                            cancelButton: 'btn btn-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.querySelector('input[name="action"]').value = action;
                            form.submit();
                        }
                    });
                };

                validateBtn.addEventListener("click", () => {
                    handleAction(
                        "validate", 
                        "Valider ce module ?", 
                        "Ce module sera ajouté à la liste des cours validés et le professeur en sera notifié.",
                        "#198754", 
                        "question"
                    );
                });

                declineBtn.addEventListener("click", () => {
                    handleAction(
                        "decline", 
                        "Refuser ce module ?", 
                        "Ce module sera rejeté et le professeur en sera notifié.",
                        "#dc3545", 
                        "warning"
                    );
                });
            });
        });
    </script>

    <style>
        .transition-300 {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-3px);
        }
        /* Button style improvements */
        .btn {
            border-radius: 4px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-success, .btn-danger, .btn-outline-danger {
            padding: 0.6rem 1rem;
        }
        .btn-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
    </style>
<?php
    return ob_get_clean();
}
?>