<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function vacantModulesView(array $modules, array $filieres, array $availableProfessors): string {
    ob_start();
    ?>

    <div class="container mt-4 px-4 px-md-5">
        <!-- Header Section with Statistics -->
        <div class="row mb-1">
            <div class="col-lg-7">
                <div class="d-flex align-items-center mb-2">
                    <div class="text-primary  me-2">
                        <i class="ti ti-alert-circle fs-6"></i>
                    </div>
                    <h2 class="fw-bold text-primary mb-0">Unités d'Enseignement Vacantes</h2>
                </div>
                <p class="text-muted ms-2 ps-4 border-start border-3 border-primary">
                    Ces modules nécessitent l'attribution d'un professeur pour le semestre en cours.
                    <?php if (!empty($availableProfessors)): ?>
                        <br><span class="text-success">Il y a <?= count($availableProfessors) ?> professeurs disponibles pour l'affectation.</span>
                    <?php endif; ?>
                </p>
            </div>
            <?php if (!empty($modules)): ?>
            <div class="col-lg-5">
                        <div class="row g-6">
                            <div class="col-5">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                        <i class="ti ti-book fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0 text-primary"><?= count($modules) ?></h3>
                                        <span class="text-muted fs-6">Modules vacants</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                        <i class="ti ti-users fs-4 text-success"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0 text-success"><?= count($availableProfessors) ?></h3>
                                        <span class="text-muted fs-6">Professeurs</span>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Search and Filter Section -->
        <?php if (!empty($modules)): ?>

                    <?= createSearchFilterComponent(
                        "Rechercher un module par nom, code ou description...",
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
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <div class="bg-light rounded-circle p-4 d-inline-flex">
                                    <i class="ti ti-check-circle text-success fs-1"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-primary mb-3">Aucun module vacant</h4>
                            <p class="text-muted mb-0">Tous les modules ont déjà été assignés à des professeurs.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($modules as $module): ?>
                    <div class="col-12 col-md-6 col-xl-4 module-card mb-2"
                        data-filiere="<?= strtolower(str_replace(' ', '_', $module['filiere_name'])) ?>"
                        data-semester="<?= strtolower($module['semester']) ?>">
                        <div class="card shadow-sm border-0 rounded-4 h-100 position-relative module-card-hover">
                                <div class="card-status-top bg-primary"></div>
                                <div class="card-body p-4"> 
                                    <!-- Module Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold text-primary mb-0">
                                            <?= htmlspecialchars($module['title']) ?>
                                        </h5>
                                        <span class="badge bg-primary text-white rounded-pill">
                                            <?= htmlspecialchars($module['code_module'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Module Tags -->
                                    <div class="d-flex gap-2 mb-3 flex-wrap">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill py-2 px-3">
                                            <i class="ti ti-bookmark me-1"></i> <?= htmlspecialchars($module['filiere_name']) ?>
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill py-2 px-3">
                                            <i class="ti ti-calendar me-1"></i> <?= formatSemester($module['semester']) ?>
                                        </span>
                                    </div>

                                    <!-- Module Description -->
                                    <div class="text-muted mb-3 module-description">
                                        <?php if (empty($module['description'])): ?>
                                            <p class="fst-italic text-muted small">Aucune description disponible</p>
                                        <?php else: ?>
                                            <p class="mb-0"><?= htmlspecialchars($module['description']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Module Details -->
                                    <div class="bg-light p-3 rounded-3 mb-4">
                                        <div class="row g-2 text-center">
                                            <div class="col-3 border-end">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted mb-1">Cours</small>
                                                    <span class="fw-bold"><?= $module['volume_cours'] ?>h</span>
                                                </div>
                                            </div>
                                            <div class="col-3 border-end">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted mb-1">TD</small>
                                                    <span class="fw-bold"><?= $module['volume_td'] ?? 0 ?>h</span>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted mb-1">TP</small>
                                                    <span class="fw-bold"><?= $module['volume_tp'] ?? 0 ?>h</span>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted mb-1">Autre</small>
                                                    <span class="fw-bold"><?= $module['volume_autre'] ?? 0 ?>h</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignment Button -->
                                    <button class="btn btn-primary btn-assign w-100" data-bs-toggle="modal" data-bs-target="#manualAssignModal" data-module-id="<?= $module['id_module'] ?>" data-module-title="<?= htmlspecialchars($module['title']) ?>">
                                        <i class="ti ti-user-plus me-2"></i> Assigner un professeur
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Assignment Modal -->
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
                        <select class="form-select form-select-lg rounded-3" name="manual_professor_id" id="professorSelect" required>
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
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i> Annuler
                    </button>
                    <button type="submit" name="manual_validate" class="btn btn-primary rounded-pill px-4">
                        <i class="ti ti-check me-1"></i> Confirmer l'assignation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
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

        // Animation for module cards
        const moduleCards = document.querySelectorAll('.module-card');
        moduleCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
        
        // Search input active state
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
    /* Card hover effects */
    .module-card .card {
        transition: all 0.3s ease;
        border: none;
    }
    
    .module-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Card status indicator */
    .card-status-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 4px 4px 0 0;
    }
    
    /* Module description container */
    .module-description {
        max-height: 80px;
        overflow-y: auto;
        font-size: 0.9rem;
    }
    
    /* Animation classes */
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
    
    /* Active search input styling */
    .active-search {
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    /* Assign button styling */
    .btn-assign {
        border-radius: 8px;
        transition: all 0.2s ease;
        font-weight: 500;
        padding: 0.6rem 1rem;
    }
    
    .btn-assign:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
    }
    
    /* Rounded elements */
    .rounded-4 {
        border-radius: 0.75rem !important;
    }
    
    /* Card shadow */
    .shadow-sm {
        box-shadow: 0 0.125rem 0.375rem rgba(0, 0, 0, 0.05) !important;
    }
    
    /* Background gradient */
    .bg-gradient {
        background: linear-gradient(135deg, rgba(var(--bs-light-rgb), 1) 0%, rgba(var(--bs-primary-rgb), 0.05) 100%);
    }
    
    /* Improved typography */
    h2, h3, h4, h5, .fw-bold {
        letter-spacing: -0.01em;
    }
    
    /* Custom scrollbar for description */
    .module-description::-webkit-scrollbar {
        width: 4px;
    }
    
    .module-description::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .module-description::-webkit-scrollbar-thumb {
        background: rgba(var(--bs-primary-rgb), 0.3);
        border-radius: 10px;
    }
    
    .module-description::-webkit-scrollbar-thumb:hover {
        background: rgba(var(--bs-primary-rgb), 0.5);
    }
    </style>

    <?php
    return ob_get_clean();
}