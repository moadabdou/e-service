<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/speciality.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$moduleModel = new ModuleModel();
$filiereModel = new FiliereModel();
$userModel = new UserModel();
$specialityModel = new SpecialityModel();

$coordinatorId = $_SESSION['id_user'] ?? null;
$modules = [];

if ($coordinatorId) {
    $filiereId = $filiereModel->getFiliereIdByCoordinator($coordinatorId);
    if ($filiereId) {
        $modules = $moduleModel->getModulesByFiliereId($filiereId);
    }
}

ob_start();
?>


<div class="container-fluid px-2 py-2 ">
    <!-- Header Section -->
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="header-icon text-primary ">
                    <i class="ti ti-list-details fs-6"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold text-primary">Gestion des Modules</h2>
                    <p class="mb-0 opacity-75">Vue d'ensemble des modules de votre filière</p>
                </div>
            </div>
            <a href="/e-service/internal/members/professor/coordonnateur/export_modules.php" class="export-btn">
                <i class="ti ti-download"></i>
                Exporter CSV
            </a>
        </div>
    </div>

    <?php if (!empty($modules)) : ?>
        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" placeholder="Rechercher un module..." id="moduleSearch">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select border-0 bg-light" id="semesterFilter">
                        <option value="">Tous les semestres</option>
                        <?php 
                        $semesters = array_unique(array_column($modules, 'semester'));
                        sort($semesters);
                        foreach($semesters as $sem): ?>
                            <option value="<?= $sem ?>">Semestre <?= $sem ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select border-0 bg-light" id="typeFilter">
                        <option value="">Tous les types</option>
                        <option value="Complet">Complet</option>
                        <option value="Sous-module">Sous-module</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Modules Table -->
        <div class="modern-card p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="modulesTable">
                    <thead>
                        <tr>
                            <th>Code Module</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Semestre</th>
                            <th>Volume</th>
                            <th>Crédits</th>
                            <th>Responsable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modules as $module) :
                            $volume_total = ($module['volume_cours'] ?? 0) + ($module['volume_td'] ?? 0) + ($module['volume_tp'] ?? 0) + ($module['volume_autre'] ?? 0);
                            $responsableName = $userModel->getFullNameById($module['responsable'] ?? 0) ?? '---';
                            $specialityTitle = $specialityModel->getTitleById($module['id_speciality'] ?? 0) ?? '---';

                            $type = 'Complet';
                            if ($module['evaluation'] != 0) {
                                $type ='Sous-module';
                            }

                            $badgeClass = [
                                'Complet' => 'badge-complete',
                                'Sous-module' => 'badge-submodule'
                            ];

                            $collapseId = 'details' . $module['id_module'];
                            $evaluationLabel = $module['evaluation'] == 0 ? 6 : 3;
                        ?>
                            <tr class="module-row" data-semester="<?= $module['semester'] ?>" data-type="<?= $type ?>">
                                <td>
                                    <code class="bg-light px-2 py-1 rounded"><?= htmlspecialchars($module['code_module']) ?></code>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?= htmlspecialchars($module['title']) ?></div>
                                </td>
                                <td>
                                    <span class="module-badge <?= $badgeClass[$type] ?>"><?= $type ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar-event me-2 text-muted"></i>
                                        <span class="fw-medium"><?= htmlspecialchars($module['semester']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="volume-indicator">
                                        <i class="ti ti-clock"></i>
                                        <?= $volume_total ?>h
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-award me-2 text-warning"></i>
                                        <span class="fw-bold"><?= htmlspecialchars($module['credits']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="ti ti-user text-white" style="font-size: 14px;"></i>
                                        </div>
                                        <span class="text-truncate" style="max-width: 120px;" title="<?= htmlspecialchars($responsableName) ?>">
                                            <?= htmlspecialchars($responsableName) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <button class="action-btn" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                                        <i class="ti ti-eye me-1"></i>
                                        Détails
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="<?= $collapseId ?>">
                                <td colspan="8" class="p-0">
                                    <div class="details-panel p-4 m-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-primary mb-3">
                                                    <i class="ti ti-info-circle me-2"></i>Description
                                                </h6>
                                                <p class="text-muted mb-4">
                                                    <?= nl2br(htmlspecialchars($module['description'] ?? 'Aucune description disponible.')) ?>
                                                </p>
                                                
                                                <h6 class="fw-bold text-primary mb-3">
                                                    <i class="ti ti-target me-2"></i>Spécialité & Évaluation
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Spécialité</small>
                                                        <div class="fw-semibold"><?= htmlspecialchars($specialityTitle) ?></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Évaluation</small>
                                                        <div class="fw-semibold"><?= $evaluationLabel ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-primary mb-3">
                                                    <i class="ti ti-clock me-2"></i>Répartition des Volumes
                                                </h6>
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <div class="bg-white p-3 rounded-3 text-center">
                                                            <i class="ti ti-presentation text-info fs-4"></i>
                                                            <div class="fw-bold fs-5"><?= $module['volume_cours'] ?>h</div>
                                                            <small class="text-muted">Cours</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-white p-3 rounded-3 text-center">
                                                            <i class="ti ti-users text-success fs-4"></i>
                                                            <div class="fw-bold fs-5"><?= $module['volume_td'] ?>h</div>
                                                            <small class="text-muted">TD</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-white p-3 rounded-3 text-center">
                                                            <i class="ti ti-flask text-warning fs-4"></i>
                                                            <div class="fw-bold fs-5"><?= $module['volume_tp'] ?>h</div>
                                                            <small class="text-muted">TP</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="bg-white p-3 rounded-3 text-center">
                                                            <i class="ti ti-dots text-secondary fs-4"></i>
                                                            <div class="fw-bold fs-5"><?= $module['volume_autre'] ?>h</div>
                                                            <small class="text-muted">Autre</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="no-modules-state">
            <div class="mb-4">
                <i class="ti ti-folder-open text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-3">Aucun module trouvé</h4>
            <p class="text-muted mb-4">Il n'y a actuellement aucun module associé à votre filière.</p>
            <button class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>Ajouter un module
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('moduleSearch');
    const semesterFilter = document.getElementById('semesterFilter');
    const typeFilter = document.getElementById('typeFilter');
    const tableRows = document.querySelectorAll('.module-row');

    function filterTable() {
        const searchTerm = searchInput?.value.toLowerCase() || '';
        const selectedSemester = semesterFilter?.value || '';
        const selectedType = typeFilter?.value || '';

        tableRows.forEach(row => {
            const moduleCode = row.querySelector('code').textContent.toLowerCase();
            const moduleTitle = row.querySelector('.fw-semibold').textContent.toLowerCase();
            const semester = row.dataset.semester;
            const type = row.dataset.type;

            const matchesSearch = moduleCode.includes(searchTerm) || moduleTitle.includes(searchTerm);
            const matchesSemester = !selectedSemester || semester === selectedSemester;
            const matchesType = !selectedType || type === selectedType;

            const shouldShow = matchesSearch && matchesSemester && matchesType;
            row.style.display = shouldShow ? '' : 'none';
            
            // Also hide/show the corresponding details row
            const nextRow = row.nextElementSibling;
            if (nextRow && nextRow.classList.contains('collapse')) {
                nextRow.style.display = shouldShow ? '' : 'none';
            }
        });
    }

    // Add event listeners
    searchInput?.addEventListener('input', filterTable);
    semesterFilter?.addEventListener('change', filterTable);
    typeFilter?.addEventListener('change', filterTable);

    // Add hover effects for better interactivity
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<style>
.modern-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 1.5rem;
}

.stats-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 12px;
    color: white;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: scale(1.05);
}

.module-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-complete {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
}



.badge-submodule {
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    color: white;
}

.modern-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.modern-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modern-table th {
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.modern-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
}

.modern-table tbody tr:hover {
    background-color: #f7fafc;
    transition: background-color 0.2s ease;
}

.details-panel {
    background: linear-gradient(145deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.action-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    color: white;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.export-btn {
    background: linear-gradient(135deg, #48bb78, #38a169);
    border: none;
    border-radius: 50px;
    color: white;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.export-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(72, 187, 120, 0.4);
    color: white;
    text-decoration: none;
}

.header-icon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    padding: 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.volume-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: #edf2f7;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
}

.search-filter-bar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.no-modules-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}



@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>


<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("ModuleListing", $content);
?>