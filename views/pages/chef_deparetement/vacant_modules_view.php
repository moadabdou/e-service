<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function vacantModulesView(array $modules, array $filieres, array $availableProfessors): string {
    ob_start();
    ?>

    <div class="container mt-2 px-4 px-md-5">

        <div class="row align-items-center mb-4">
            <div class="col-lg-6">
                <h2 class="fw-bold text-primary d-flex align-items-center gap-2 mb-1">
                    <i class="ti ti-alert-circle"></i> Unités vacantes
                </h2>
                <p class="text-muted">Modules sans professeur assigné</p>
            </div>
            <?php if (!empty($modules)): ?>
            <div class="col-lg-6 text-lg-end mt-2 mt-lg-0">
                <div class="d-inline-flex gap-3 align-items-center">
                    <div class="py-2 px-3 bg-light rounded-3 text-center">
                        <h4 class="mb-0 fw-bold text-primary"><?= count($modules) ?></h4>
                        <small class="text-muted">Modules vacants</small>
                    </div>
                    <div class="py-2 px-3 bg-light rounded-3 text-center">
                        <h4 class="mb-0 fw-bold text-success"><?= count($availableProfessors) ?></h4>
                        <small class="text-muted">Professeurs disponibles</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
                <?php if (!empty($modules)): ?>
                <?= createSearchFilterComponent(
                    "Rechercher un module...",
                    [
                        "filiere" => [
                            "label" => "Filière",
                            "icon" => "ti-bookmark",
                            "allLabel" => "Toutes les filières",
                            "options" => array_map(fn($f) => [
                                "value" => strtolower(str_replace(' ', '_', $f['filiere_name'])),
                                "label" => $f['filiere_name']
                            ], $filieres)
                        ],
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
                        ]
                    ],
                    "moduleCards",
                    "module-card"
                ) ?>
                <?php endif; ?>
        <!-- Module Cards -->
        <div class="row g-4" id="moduleCards">
            <?php if (empty($modules)): ?>
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm rounded-4 d-flex align-items-center" role="alert">
                        <i class="ti ti-info-circle fs-3 me-3"></i>
                        <div>
                            <h5 class="mb-1">Aucun module vacant</h5>
                            <p class="mb-0">Tous les modules ont déjà été assignés à des professeurs.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($modules as $module): ?>
                    <div class="col-12 col-md-6 col-xl-4 module-card"
                        data-filiere="<?= strtolower(str_replace(' ', '_', $module['filiere_name'])) ?>"
                        data-semester="<?= strtolower($module['semester']) ?>">
                        <div class="card shadow-sm border-0 rounded-4 h-100 position-relative module-card-hover">
                                <div class="card-status-top bg-primary"></div>
                                <div class="card-body p-4 d-flex flex-column"> 
                                    <h5 class="fw-bold text-primary mt-1 mb-3 d-flex justify-content-between">
                                        <span><?= htmlspecialchars($module['title']) ?></span>
                                        <span class="badge bg-light text-primary">
                                            <?= $module['volume_cours'] ?>h
                                        </span>
                                    </h5>

                                    <div class="d-flex gap-2 mb-3 flex-wrap">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill py-2 px-3">
                                            <i class="ti ti-bookmark me-1"></i> <?= htmlspecialchars($module['filiere_name']) ?>
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill py-2 px-3">
                                            <i class="ti ti-calendar me-1"></i> <?= formatSemester($module['semester']) ?>
                                        </span>
                                    </div>

                                    <div class="text-muted mb-2 module-description">
                                    <h6 class="fw-medium mb-1">Description:</h6>
                                        <?php if (empty($module['description'])): ?>
                                            <p class="fst-italic">Aucune description disponible</p>
                                        <?php else: ?>
                                            <p><?= htmlspecialchars($module['description']) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-auto"> 
                                        <button class="btn btn-primary btn-assign w-100" data-bs-toggle="modal" data-bs-target="#manualAssignModal" data-module-id="<?= $module['id_module'] ?>" data-module-title="<?= htmlspecialchars($module['title']) ?>">
                                            <i class="ti ti-user-plus me-1"></i> Assigner un professeur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="manualAssignModal" tabindex="-1" aria-labelledby="manualAssignLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content rounded-4 shadow border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="manualAssignLabel">
                        <i class="ti ti-user-check me-2"></i>Assigner un professeur
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="manual_module_id" id="manualModuleId">
                    
                    <div class="alert alert-primary border-0 rounded-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2 fs-4"></i>
                            <div>
                                <strong>Module:</strong> <span id="moduleTitle"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="professorSelect" class="form-label fw-semibold">
                            <i class="ti ti-users me-1"></i> Sélectionner un professeur :
                        </label>
                        <select class="form-select form-select-lg" name="manual_professor_id" id="professorSelect" required>
                            <option value="" disabled selected>Choisir un professeur</option>
                            <?php if (empty($availableProfessors)): ?>
                                <option value="" disabled>Aucun professeur disponible</option>
                            <?php else: ?>
                                <?php foreach ($availableProfessors as $prof): ?>
                                    <option value="<?= $prof['id_user'] ?>">
                                        <?= htmlspecialchars($prof['firstName'] . ' ' . $prof['lastName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Annuler
                    </button>
                    <button type="submit" name="manual_validate" class="btn btn-primary px-4">
                        <i class="ti ti-check me-1"></i> Confirmer l'assignation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const manualAssignModal = document.getElementById('manualAssignModal');
        if (manualAssignModal) {
            manualAssignModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const moduleId = button.getAttribute('data-module-id');
                const moduleTitle = button.getAttribute('data-module-title');
                
                document.getElementById('manualModuleId').value = moduleId;
                document.getElementById('moduleTitle').textContent = moduleTitle;
            });
        }

        const moduleCards = document.querySelectorAll('.module-card');
        moduleCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
        
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.add('active-search');
                } else {
                    this.classList.remove('active-search');
                }
            });
        }
    });
    </script>

    <style>
    .module-card .card {
        transition: all 0.3s ease;
    }
    
    .module-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }
    
    .card-status-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 4px 4px 0 0;
    }
    
    .module-description {
        max-height: 100px;
        overflow-y: auto;
    }
    
    .animate__animated {
        animation-duration: 0.65s;
        animation-fill-mode: both;
    }
    
    .animate__fadeInUp {
        animation-name: fadeInUp;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 30px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    .active-search {
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    .btn-assign {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .btn-assign:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
    }
    </style>

    <?php
    return ob_get_clean();
}
