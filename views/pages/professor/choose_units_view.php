<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function chooseUnitsFormView($filliers, $availableModules, $selectedModules, $errors, $info, $totalHours, $minHours, $maxHours,$deadline) {
    ob_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($errors)) {
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
    ?>

    <div class="container mt-2 px-4 px-md-5">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                    <div class="fw-bold text-primary mb-2 mb-md-0">
                    <h2 class="fw-bold text-primary mb-0">
                        <i class="ti ti-bookmarks "></i>
                        Choisir mes modules</h2>
                    </div>
                    <a href="/e-service/internal/members/professor/selected_units.php" 
                       class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-2 shadow-sm">
                        <i class="ti ti-clipboard-list"></i> 
                        <span class="d-none d-sm-inline">Voir les modules sélectionnés</span>
                    </a>
                </div>

                <!-- Hours Counter Card -->
                <?php if ($totalHours > 0) :
                    $progressPercentage = min(100, ($totalHours / $maxHours) * 100);
                    $progressColor = 'bg-primary';
                    $cardClass = 'border-primary';
                    $iconClass = 'text-primary';
                    
                    if ($totalHours < $minHours) {
                        $progressColor = 'bg-warning';
                        $cardClass = 'border-warning';
                        $iconClass = 'text-warning';
                    } elseif ($totalHours > $maxHours) {
                        $progressColor = 'bg-danger';
                        $cardClass = 'border-danger';
                        $iconClass = 'text-danger';
                    } elseif ($totalHours >= $minHours && $totalHours <= $maxHours) {
                        $progressColor = 'bg-success';
                        $cardClass = 'border-success';
                        $iconClass = 'text-success';
                    }
                ?>
                <div class="card shadow-sm border-0 rounded-4 mb-4 <?= $cardClass ?>">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3 mb-3 mb-md-0">
                                <div class="rounded-circle p-2 bg-light <?= $iconClass ?>">
                                    <i class="ti ti-clock fs-6"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Charge horaire totale choisi</h5>
                                    <p class="text-muted mb-0">
                                        <?= $minHours ?>h minimum - <?= $maxHours ?>h maximum
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <h3 class="display-6 fw-bold mb-0 me-2 <?= $iconClass ?>">
                                    <?= htmlspecialchars($totalHours) ?>
                                </h3>
                                <span class="fs-5 text-muted">heures</span>
                            </div>
                        </div>
                        
                        <div class="progress rounded-pill" style="height: 10px;">
                            <div class="progress-bar <?= $progressColor ?>" role="progressbar" 
                                 style="width: <?= $progressPercentage ?>%;" 
                                 aria-valuenow="<?= $totalHours ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="<?= $maxHours ?>">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">0h</small>
                            <?php if ($totalHours < $minHours): ?>
                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                    <i class="ti ti-alert-triangle me-1"></i>
                                    Minimum <?= htmlspecialchars($minHours) ?>h requis
                                </span>
                            <?php elseif ($totalHours > $maxHours): ?>
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                    <i class="ti ti-alert-circle me-1"></i>
                                    Maximum <?= htmlspecialchars($maxHours) ?>h dépassé
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                    <i class="ti ti-check me-1"></i>
                                    Charge horaire valide
                                </span>
                            <?php endif; ?>
                            <small class="text-muted"><?= $maxHours ?>h</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($deadline): ?>
                        <div class="alert alert-<?= htmlspecialchars($deadline['type']) ?> text-center shadow-sm rounded-4 p-4">
                            <i class="ti ti-alert-circle fs-6 d-block mb-3"></i>
                            <h5><?= htmlspecialchars($deadline['msg']) ?></h5>
                            <p class="text-muted mb-0"><?= htmlspecialchars($deadline['desc']) ?></p>
                        </div>
                <?php else : ?>
                <!-- Alerts Section -->
                <?php if ($info) : ?>
                    <div class="alert alert-<?= htmlspecialchars($info['type']) ?> alert-dismissible fade show rounded-4 shadow-sm border-start border-5 border-<?= htmlspecialchars($info['type']) ?>" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2 fs-4"></i>
                            <div><?= htmlspecialchars($info['msg']) ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Module Selection Form -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                        <h4 class="fw-bold mb-0">
                            <i class="ti ti-list-check me-2 text-primary"></i>
                            Modules disponibles
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" id="moduleSelectionForm">
                            <?php if (empty($availableModules)) : ?>
                                <div class="alert alert-warning text-center rounded-4">
                                    <i class="ti ti-mood-empty fs-5 mb-2 d-block text-warning opacity-75"></i>
                                    <h5 class="mb-0">Aucun module disponible</h5>
                                    <p class="text-muted mt-2 mb-0">Aucun module n'est disponible pour votre département actuellement.</p>
                                </div>
                            <?php else : ?>
                                <!-- Search & Filter Component -->
                                <?= createSearchFilterComponent(
                                    "Rechercher par titre, filière...",
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
                                        ]
                                    ],
                                    "modulesList",
                                    "filterable-item",
                                    "moduleCount"
                                ); ?>

                                <!-- Modules List -->
                                <div id="modulesList" class="mt-2">
                                    <?php foreach ($availableModules as $module) : 
                                        $isSelected = in_array($module['id_module'], array_column($selectedModules, 'id_module'));
                                        $moduleType = isset($module['evaluation']) && $module['evaluation'] == 0 ? 'Module complet' : 'Sous-module';
                                        $totalHours = isset($module['total_hours']) ? $module['total_hours'] : 
                                            ((isset($module['volume_cours']) ? $module['volume_cours'] : 0) + 
                                            (isset($module['volume_td']) ? $module['volume_td'] : 0) + 
                                            (isset($module['volume_tp']) ? $module['volume_tp'] : 0) + 
                                            (isset($module['volume_autre']) ? $module['volume_autre'] : 0));
                                    ?>
                                        <div class="module-card mb-3 filterable-item transition-hover"
                                            data-semester="<?= htmlspecialchars(strtolower($module['semester'] ?? '')) ?>"
                                            data-filiere="<?= htmlspecialchars(strtolower(str_replace(' ', '_', $module['filiere_name']))) ?>">
                                            <div class="card rounded-4 border <?= $isSelected ? 'border-primary shadow' : 'border-light shadow-sm' ?>">
                                                <div class="card-body position-relative">
                                                    <!-- Module Selection Checkbox -->
                                                    <div class="position-absolute top-0 end-0 p-3">
                                                        <div class="form-check form-switch">
                                                            <input 
                                                                class="form-check-input module-checkbox" 
                                                                type="checkbox" 
                                                                name="modules[]" 
                                                                value="<?= htmlspecialchars($module['id_module']) ?>"
                                                                data-hours="<?= htmlspecialchars($module['volume_cours']) ?>"
                                                                id="module-<?= htmlspecialchars($module['id_module']) ?>"
                                                                <?= $isSelected ? 'checked' : '' ?>
                                                            >
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Module Header with Code -->
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="me-3">
                                                            <div class="rounded-circle p-2 <?= $isSelected ? 'bg-primary-subtle text-primary' : 'bg-light text-muted' ?>">
                                                                <i class="ti ti-book fs-4"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 small">
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-primary text-white rounded-pill me-3">
                                                                    <?= htmlspecialchars($module['code_module'] ?? 'N/A') ?>
                                                                </span>
                                                                <h5 class="card-title fw-bold mb-0 <?= $isSelected ? 'text-primary' : '' ?>">
                                                                    <?= htmlspecialchars($module['title']) ?>
                                                                </h5>
                                                            </div>
                                                            
                                                            <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                                                <span class="badge bg-info-subtle text-info rounded-pill">
                                                                    <?= formatSemester($module['semester'] ?? '') ?>
                                                                </span>
                                                                <span class="badge bg-light text-dark rounded-pill">
                                                                    <?= htmlspecialchars($module['filiere_name'] ?? 'Aucune') ?>
                                                                </span>
                                                                <span class="badge <?= $moduleType == 'Module complet' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?> rounded-pill">
                                                                    <?= $moduleType ?>
                                                                </span>
                                                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                                                    <?= htmlspecialchars($module['credits'] ?? '0') ?> crédits
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Module Description -->
                                                    <div class="description-container mb-3">
                                                        <div class="module-description text-muted">
                                                            <?= !empty($module['description']) ? htmlspecialchars($module['description']) : 
                                                            '<span class="fst-italic">Aucune description disponible pour ce module.</span>' ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Module Hours Details -->
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6 col-md-3">
                                                            <div class="p-2 rounded bg-light-subtle border">
                                                                <div class="small text-muted">Cours</div>
                                                                <div class="fw-bold"><?= htmlspecialchars($module['volume_cours'] ?? '0') ?> h</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <div class="p-2 rounded bg-light-subtle border">
                                                                <div class="small text-muted">TD</div>
                                                                <div class="fw-bold"><?= htmlspecialchars($module['volume_td'] ?? '0') ?> h</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <div class="p-2 rounded bg-light-subtle border">
                                                                <div class="small text-muted">TP</div>
                                                                <div class="fw-bold"><?= htmlspecialchars($module['volume_tp'] ?? '0') ?> h</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <div class="p-2 rounded bg-light-subtle border">
                                                                <div class="small text-muted">Autre</div>
                                                                <div class="fw-bold"><?= htmlspecialchars($module['volume_autre'] ?? '0') ?> h</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Module Selection & Total Hours -->
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <label for="module-<?= htmlspecialchars($module['id_module']) ?>" class="btn btn-sm <?= $isSelected ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill">
                                                            <i class="ti <?= $isSelected ? 'ti-check' : 'ti-plus' ?> me-1"></i>
                                                            <?= $isSelected ? 'Module sélectionné' : 'Sélectionner ce module' ?>
                                                        </label>
                                                        <div class="badge bg-secondary rounded-pill fs-4">
                                                            <i class="ti ti-clock me-1"></i> Total: <?= htmlspecialchars($totalHours) ?> heures
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if (isset($errors['modules'])) : ?>
                                    <div class="alert alert-danger rounded-4 mt-2">
                                        <i class="ti ti-alert-triangle me-2"></i>
                                        <?= htmlspecialchars($errors['modules']) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Submit Button -->
                                <div class="text-center mt-2">
                                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow-sm">
                                        <i class="ti ti-check me-2 fs-5"></i>
                                        Valider mes choix
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.module-checkbox');
        const totalHoursDisplay = document.querySelector('.display-6');
        let currentTotalHours = <?= $totalHours ?>;
        const minHours = <?= $minHours ?>;
        const maxHours = <?= $maxHours ?>;
        
        // Handle checkbox change events
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const moduleHours = parseInt(this.dataset.hours);
                const moduleCard = this.closest('.module-card').querySelector('.card');
                const moduleIcon = moduleCard.querySelector('.rounded-circle');
                const moduleTitle = moduleCard.querySelector('.card-title');
                const moduleLabel = moduleCard.querySelector('label');
                
                // Update module card visual state
                if (this.checked) {
                    moduleCard.classList.add('border-primary', 'shadow');
                    moduleCard.classList.remove('border-light', 'shadow-sm');
                    moduleIcon.classList.add('bg-primary-subtle', 'text-primary');
                    moduleIcon.classList.remove('bg-light', 'text-muted');
                    moduleTitle.classList.add('text-primary');
                    moduleLabel.classList.add('btn-primary');
                    moduleLabel.classList.remove('btn-outline-primary');
                    moduleLabel.innerHTML = '<i class="ti ti-check me-1"></i> Module sélectionné';
                    currentTotalHours += moduleHours;
                } else {
                    moduleCard.classList.remove('border-primary', 'shadow');
                    moduleCard.classList.add('border-light', 'shadow-sm');
                    moduleIcon.classList.remove('bg-primary-subtle', 'text-primary');
                    moduleIcon.classList.add('bg-light', 'text-muted');
                    moduleTitle.classList.remove('text-primary');
                    moduleLabel.classList.remove('btn-primary');
                    moduleLabel.classList.add('btn-outline-primary');
                    moduleLabel.innerHTML = '<i class="ti ti-plus me-1"></i> Sélectionner ce module';
                    currentTotalHours -= moduleHours;
                }
                
                // Update total hours display if it exists
                if (totalHoursDisplay) {
                    totalHoursDisplay.textContent = currentTotalHours;
                    
                    // Update progress bar
                    const progressBar = document.querySelector('.progress-bar');
                    const progressPercentage = Math.min(100, (currentTotalHours / maxHours) * 100);
                    progressBar.style.width = progressPercentage + '%';
                    
                    // Update status badge
                    const statusBadge = document.querySelector('.badge:not(.bg-secondary-subtle):not(.bg-info-subtle):not(.bg-light)');
                    
                    if (currentTotalHours < minHours) {
                        progressBar.className = 'progress-bar bg-warning';
                        statusBadge.className = 'badge bg-warning-subtle text-warning px-3 py-2 rounded-pill';
                        statusBadge.innerHTML = '<i class="ti ti-alert-triangle me-1"></i> Minimum ' + minHours + 'h requis';
                        totalHoursDisplay.className = 'display-6 fw-bold mb-0 me-2 text-warning';
                    } else if (currentTotalHours > maxHours) {
                        progressBar.className = 'progress-bar bg-danger';
                        statusBadge.className = 'badge bg-danger-subtle text-danger px-3 py-2 rounded-pill';
                        statusBadge.innerHTML = '<i class="ti ti-alert-circle me-1"></i> Maximum ' + maxHours + 'h dépassé';
                        totalHoursDisplay.className = 'display-6 fw-bold mb-0 me-2 text-danger';
                    } else {
                        progressBar.className = 'progress-bar bg-success';
                        statusBadge.className = 'badge bg-success-subtle text-success px-3 py-2 rounded-pill';
                        statusBadge.innerHTML = '<i class="ti ti-check me-1"></i> Charge horaire valide';
                        totalHoursDisplay.className = 'display-6 fw-bold mb-0 me-2 text-success';
                    }
                }
            });
        });
        
        // Handle description text truncation
        document.querySelectorAll('.module-description').forEach(desc => {
            if (desc.scrollHeight > 60 || desc.textContent.length > 150) {
                const fullContent = desc.innerHTML;
                const truncatedContent = desc.textContent.substring(0, 150) + '...';
                
                desc.innerHTML = truncatedContent;
                desc.style.height = 'auto';
            }
        });
    });
    </script>
    
    <style>
    .transition-hover {
        transition: all 0.2s ease-in-out;
    }
    .module-card:hover .card {
        transform: translateY(-2px);
    }
    .module-description {
        line-height: 1.5;
        font-size: 0.9rem;
    }
    .progress {
        height: 10px;
        background-color: #f0f0f0;
    }
    .rounded-circle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    </style>
<?php
    return ob_get_clean();
}
?>