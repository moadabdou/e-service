<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function pendingModulesView(array $pendingModules, array $filliers, ?array $info = null): string {
    ob_start();

    // Extraire les professeurs distincts pour le filtre
    $professorOptions = [];
    foreach ($pendingModules as $mod) {
        $label = $mod['firstName'] . ' ' . $mod['lastName'];
        $value = strtolower(str_replace(' ', '_', $label));
        if (!in_array($value, array_column($professorOptions, 'value'))) {
            $professorOptions[] = [
                "value" => $value,
                "label" => $label
            ];
        }
    }
?>
    <div class="container mt-2 px-4 px-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h2 class="fw-bold text-primary mb-2 mb-md-0">
                <i class="ti ti-hourglass"></i> Modules en attente de validation
            </h2>
        </div>

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

        <?php if ($info): ?>
            <div class="alert alert-<?= htmlspecialchars($info['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($info['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($pendingModules)) : ?>
            <div class="alert alert-warning text-center shadow-sm">
                <i class="ti ti-alert-circle"></i> Aucun module en attente pour le moment.
            </div>
        <?php else : ?>
            <div class="row g-4" id="listContainer">
                <?php foreach ($pendingModules as $module) :
                    $professorSlug = strtolower(str_replace(' ', '_', $module['firstName'] . ' ' . $module['lastName']));
                ?>
                    <div class="col-12 col-md-6 col-lg-4 filterable-item"
                        data-semester="<?= htmlspecialchars(strtolower($module['semester'])) ?>"
                        data-filiere="<?= htmlspecialchars(strtolower(str_replace(' ', '_', $module['filiere_name']))) ?>"
                        data-professor="<?= htmlspecialchars($professorSlug) ?>">
                        <div class="card h-100 shadow rounded-4 border-0">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title text-primary fw-bold mb-3">
                                        <?= htmlspecialchars($module['module_title']) ?>
                                        <span class="badge bg-info ms-2"><?= htmlspecialchars($module['volume_horaire']) ?>h</span>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="ti ti-user"></i> <strong>Prof :</strong> <?= htmlspecialchars($module['firstName'] . " " . $module['lastName']) ?><br>
                                        <i class="ti ti-mail"></i> <?= htmlspecialchars($module['email']) ?><br>
                                        <strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name']) ?><br>
                                        <strong>Semestre :</strong> <?= formatSemester($module['semester']) ?><br>
                                        <strong>Description :</strong> <?= htmlspecialchars($module['description'] ?? 'Aucune') ?>
                                    </p>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <form method="POST" class="d-flex gap-5 module-form px-3  ">
                                        <input type="hidden" name="module_id" value="<?= $module['id_module'] ?>">
                                        <input type="hidden" name="professor_id" value="<?= $module['by_professor'] ?>">
                                        <input type="hidden" name="action" value="">
                                        <button type="button" class="btn btn-success  validate-btn shadow-sm">
                                            <i class="ti ti-circle-check"></i> Valider
                                        </button>
                                        <button type="button" class="btn btn-danger  decline-btn shadow-sm">
                                            <i class="ti  ti-circle-x"></i> Refuser
                                        </button>
                                    </form>
                                    </div>
                                    <a href="/e-service/internal/members/professor/profile.php?id=<?= $module['by_professor'] ?>" class="btn btn-outline-primary shadow-sm">
                                        <i class="ti ti-user-circle"></i> Voir profil 
                                    </a>
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
        document.querySelectorAll(".module-form").forEach(form => {
            const validateBtn = form.querySelector(".validate-btn");
            const declineBtn = form.querySelector(".decline-btn");

            const handleAction = (action, message, confirmButtonColor) => {
                Swal.fire({
                    title: "Confirmation",
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Oui, confirmer"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.querySelector('input[name="action"]').value = action;
                        form.submit();
                    }
                });
            };

            validateBtn.addEventListener("click", () => {
                handleAction("validate", "Êtes-vous sûr de vouloir valider ce module ?", "#198754");
            });

            declineBtn.addEventListener("click", () => {
                handleAction("decline", "Êtes-vous sûr de vouloir refuser ce module ?", "#dc3545");
            });
        });
    </script>
<?php
    return ob_get_clean();
}
?>
