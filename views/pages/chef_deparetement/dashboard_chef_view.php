<?php
function departmentHeadDashboard(
    array $workloadDistribution = [],
    array $moduleChoicesStats = [],
    array $validationStats = [],
    array $professorStats = [],
    array $modulesData = [],
    array $recentActivities = [],
    int $totalProfsCount = 0,
    int $totalHoursAssigned = 0,
    int $pendingValidations = 0,
    array $modules,
    int $vacantModulesCount
): string {
    
    $workloadDistribution = is_array($workloadDistribution) ? $workloadDistribution : [];
    $moduleChoicesStats = is_array($moduleChoicesStats) ? $moduleChoicesStats : [];
    $validationStats = is_array($validationStats) ? $validationStats : [];
    $professorStats = is_array($professorStats) ? $professorStats : [];
    $modulesData = is_array($modulesData) ? $modulesData : [];
    $recentActivities = is_array($recentActivities) ? $recentActivities : [];
    

    $validatedModules = 0;
    $totalModulesCount = 0;
    
    foreach ($moduleChoicesStats as $stat) {
        if (isset($stat['count'])) {
            $totalModulesCount += $stat['count'];
            if (isset($stat['status']) && $stat['status'] === 'validated') {
                $validatedModules += $stat['count'];
            }
        }
    }
    $vacantModules = $vacantModulesCount ?? 0;
    $totalModules = $totalModulesCount ?? 1; 

    $vacantRate = round(($vacantModules / $totalModules) * 100);
    $assignmentRate = ($totalModulesCount > 0) ? round(($validatedModules/ $totalModulesCount) * 100) : 0;
    
    $validatedChoices = 0;
    foreach ($validationStats as $stat) {
        if (isset($stat['validated'])) {
            $validatedChoices += $stat['validated'];
        }
    }
    $totalChoices = $totalModulesCount; 
    $validationRate = ($totalChoices > 0) ? round(($validatedChoices / $totalChoices) * 100) : 0;
    
    // Calculate CM/TD/TP hours 
    $tdTpHours = 0; 
    $cmHours = $totalHoursAssigned - $tdTpHours; 
    
    $workloadJson = json_encode($workloadDistribution, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    $moduleChoicesJson = json_encode($moduleChoicesStats, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    $validationStatsJson = json_encode($validationStats, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    
    function getActivityIconClass($status) {
        $status = strtolower($status ?? '');
        if ($status === 'validated') return 'ti ti-circle-check';
        if ($status === 'in progress') return 'ti ti-clock';
        if ($status === 'declined') return 'ti ti-circle-x';
        return 'ti ti-circle';
    }
    
    ob_start();
?>
<link rel="stylesheet" href="/e-service/resources/assets/css/chef_dashboard.css">

<div class="dashboard-container">
    <div class="welcome-header">
        <div class="welcome-content">
            <h1>Bienvenue, Chef de département</h1>
            <p>Gérez les affectations d'enseignements pour l'année académique <?= htmlspecialchars(date('Y')) ?></p>
            
            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="ti ti-users"></i>
                    </div>
                    <div class="stat-info">
                        <h2><?= $totalProfsCount ?></h2>
                        <p>Enseignants actifs</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h2><?= $totalHoursAssigned ?> h</h2>
                        <p>Volume total assigné</p>
                    </div>
                </div>
            </div>
            
            <button class="btn-new-assignment">
                <a href="/e-service/internal/members/professor/chef_deparetement/assign_modules.php"><i class="ti ti-plus"></i> Nouvelle affectation </a>
            </button>
        </div>
        
        <div class="welcome-decoration">
            <!-- Decorative elements -->
            <div class="decoration-circle"></div>
            <div class="decoration-square"></div>
        </div>
    </div>
    
    <!-- Key Metrics Section -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon blue">
                    <i class="ti ti-book-off"></i>
                </div>
                <div>
                    <p>Taux des modules vacants</p>
                    <h3><?= $vacantRate ?>%</h3>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= $vacantRate ?>%;"></div>
                </div>
                <div class="progress-info">
                    <span><?= $vacantModules ?> modules</span>
                    <span>sur <?= $totalModulesCount ?> modules</span>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon green">
                    <i class="ti ti-circle-check"></i>
                </div>
                <div>
                    <p>Taux de validation</p>
                    <h3><?= $validationRate ?>%</h3>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill success" style="width: <?= $validationRate ?>%;"></div>
                </div>
                <div class="progress-info">
                    <span><?= $validatedChoices ?> validés</span>
                    <span>sur <?= $totalChoices ?> choix</span>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon orange">
                    <i class="ti ti-clock"></i>
                </div>
                <div>
                    <p>Volume horaire total</p>
                    <h3><?= $totalHoursAssigned ?> h</h3>
                </div>
            </div>
            <div class="hours-distribution">
                <div class="hours-type">
                    <span class="hours-label">CM:</span>
                    <span class="hours-value"><?= $cmHours ?>h</span>
                </div>
                <div class="hours-type">
                    <span class="hours-label">TD/TP:</span>
                    <span class="hours-value"><?= $tdTpHours ?>h</span>
                </div>
            </div>
            <div class="progress-container stacked">
                <div class="progress-bar">
                    <div class="progress-fill primary" style="width: <?= ($totalHoursAssigned > 0) ? ($cmHours / $totalHoursAssigned) * 100 : 0 ?>%;"></div>
                    <div class="progress-fill success" style="width: <?= ($totalHoursAssigned > 0) ? ($tdTpHours / $totalHoursAssigned) * 100 : 0 ?>%;"></div>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon red">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <div>
                    <p>Attention requise</p>
                    <h3><?= $pendingValidations ?></h3>
                </div>
            </div>
            <div class="alert-message">
                <p>Validations en attente à traiter</p>
            </div>
            <a href="/e-service/internal/members/professor/chef_deparetement/assign_modules.php">
            <button class="btn-view-requests">
                <i class="ti ti-eye"></i> Voir les demandes
            </button>
            </a>
        </div>
    </div>
    
    <!-- Main Dashboard Content -->
    <div class="dashboard-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="overview">
                <i class="ti ti-layout-dashboard"></i> Vue d'ensemble
            </button>
            <button class="tab-btn" data-tab="professors">
                <i class="ti ti-users"></i> Enseignants
            </button>
            <button class="tab-btn" data-tab="modules">
                <i class="ti ti-book"></i> Modules
            </button>
            <button class="tab-btn" data-tab="reports">
                <i class="ti ti-report-analytics"></i> Rapports
            </button>
        </div>
        
        <div class="tabs-content">
            <!-- Overview Tab (Active by Default) -->
            <div class="tab-pane active" id="overview">
                <div class="dashboard-grid">
                    <!-- Workload Distribution Chart -->
                    <div class="dashboard-card ">
                        <div class="card-header">
                            <div>
                                <h4>Répartition des Charges</h4>
                                <p>Distribution du volume horaire par statut</p>
                            </div>
                            <div class="card-actions">
                                <button class="btn-card-action">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="workloadChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
<!-- Professors with Underload or Overload -->
<div class="dashboard-card large">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="text-primary mb-0">Enseignants en Sous-charge ou Surcharge</h4>
            <small class="text-muted">Suivi du volume horaire attribué</small>
        </div>
        <div class="mt-5 d-flex justify-content-center">
            <a href="/e-service/internal/members/professor/chef_deparetement/workload.php" class="btn btn-outline-warning btn-sm d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="ti ti-eye"></i> Voir
            </a>
        </div>

    </div>
    <div class="card-body scrollable">
        <div class="professors-list">
            <?php 
            $criticalProfs = array_filter($professorStats, function($prof) {
                $assigned = (int)($prof['assigned_hours'] ?? 0);
                $min = (int)($prof['min_hours'] ?? 0);
                $max = (int)($prof['max_hours'] ?? 0);
                return $assigned < $min || $assigned > $max;
            });

            if (!empty($criticalProfs)): 
                foreach (array_slice($criticalProfs, 0, 5) as $prof):
                    $profName = htmlspecialchars($prof['firstName'] . ' ' . $prof['lastName']);
                    $assigned = (int)($prof['assigned_hours'] ?? 0);
                    $min = (int)($prof['min_hours'] ?? 0);
                    $max = (int)($prof['max_hours'] ?? 0);

                    $status = '';
                    $barClass = '';
                    $progress = 0;
                    $infoLabel = '';

                    if ($assigned < $min) {
                        $status = 'Sous-charge';
                        $barClass = 'warning';
                        $progress = min(100, ($assigned / max(1, $min)) * 100);
                        $infoLabel = ($min - $assigned) . "h manquant(s)";
                    } elseif ($assigned > $max) {
                        $status = 'Surcharge';
                        $barClass = 'danger';
                        $progress = min(100, ($assigned / max(1, $max)) * 100);
                        $infoLabel = ($assigned - $max) . "h en surplus";
                    }
            ?>
            <div class="professor-item">
                <div class="professor-avatar <?= $barClass ?>">
                    <?= strtoupper(substr($profName, 0, 1)) ?>
                </div>
                <div class="professor-info">
                    <div class="professor-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= $profName ?></h5>
                        <span class="badge bg-light text-dark border"><?= $assigned ?>h</span>
                    </div>
                    <div class="professor-progress mt-2">
                        <div class="progress-bar rounded-pill">
                            <div class="progress-fill <?= $barClass ?>" style="width: <?= $progress ?>%;"></div>
                        </div>
                        <div class="progress-info d-flex justify-content-between mt-1">
                            <small>Min: <?= $min ?>h / Max: <?= $max ?>h</small>
                            <small class="text-muted"><?= $infoLabel ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else: 
            ?>
            <div class="empty-state text-center py-4">
                <i class="ti ti-mood-happy text-success fs-1 mb-2"></i>
                <p class="text-muted mb-0">Aucun professeur en sous/surcharge actuellement.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (count($criticalProfs) > 5): ?>
    <div class="card-footer text-center">
        <button class="btn btn-outline-secondary btn-sm">Voir tous les enseignants concernés</button>
    </div>
    <?php endif; ?>
</div>

                    
                    <!-- Validation Timeline Chart -->
                    <div class="dashboard-card large">
                        <div class="card-header">
                            <div>
                                <h4>Évolution des Validations</h4>
                                <p>Modules validés par période</p>
                            </div>
                            <div class="period-selector">
                                <button class="period-btn active">Annuel</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="validationChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Module Status Chart -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <div>
                                <h4>Statut des Modules</h4>
                                <p>Répartition par état d'affectation</p>
                            </div>
                        </div>
                        <div class="card-body centered">
                            <div class="donut-chart-container">
                                <canvas id="moduleStatusChart"></canvas>
                            </div>
                            <div class="chart-legend" id="moduleStatusLegend"></div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h4>Actions Rapides du Chef</h4>
                        </div>
                        <div class="card-body">
                            <div class="quick-actions-grid">
                                
                                <a href="/e-service/internal/members/professor/chef_deparetement/assign_modules.php" class="quick-action">
                                    <div class="action-icon green">
                                        <i class="ti ti-link"></i>
                                    </div>
                                    <span>Affecter Modules</span>
                                </a>
                                
                                <a href="/e-service/internal/members/professor/chef_deparetement/professors_list.php" class="quick-action">
                                    <div class="action-icon blue">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <span>Professeurs & Charges</span>
                                </a>
                                
                                <a href="/e-service/internal/members/professor/chef_deparetement/vacant_modules.php" class="quick-action">
                                    <div class="action-icon purple">
                                        <i class="ti ti-book-off"></i>
                                    </div>
                                    <span>Voir Modules Vacants</span>
                                </a>
                                
                                <a href="/e-service/internal/members/professor/chef_deparetement/history.php" class="quick-action">
                                    <div class="action-icon orange">
                                        <i class="ti ti-calendar-stats"></i>
                                    </div>
                                    <span>Historique des années</span>
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="dashboard-card large">
                        <div class="card-header">
                            <div>
                                <h4>Activités Récentes</h4>
                                <p>Dernières actions enregistrées</p>
                            </div>
                            <a href="#" class="card-link">Voir tout</a>
                        </div>
                        <div class="card-body scrollable">
                            <div class="activities-list">
                                <?php if (!empty($recentActivities)): ?>
                                    <?php foreach(array_slice($recentActivities, 0, 7) as $activity): ?>
                                        <?php
                                            $user = htmlspecialchars($activity['user'] ?? 'Système');
                                            $module = htmlspecialchars($activity['module'] ?? '');
                                            $status = $activity['status'] ?? '';
                                            $timestamp = htmlspecialchars($activity['timestamp'] ?? date('Y-m-d'));
                                            
                                            $activityClass = 'default';
                                            if ($status === 'validated') $activityClass = 'success';
                                            elseif ($status === 'in progress') $activityClass = 'warning';
                                            elseif ($status === 'declined') $activityClass = 'danger';
                                        ?>
                                        <div class="activity-item">
                                            <div class="activity-icon <?= $activityClass ?>">
                                                <i class="<?= getActivityIconClass($status) ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <p>
                                                    <strong><?= $user ?></strong>
                                                    a changé le statut du module
                                                    <span class="activity-detail"><?= $module ?></span>
                                                    à <strong><?= ucfirst($status) ?></strong>
                                                </p>
                                                <span class="activity-date"><?= $timestamp ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="ti ti-clipboard-off"></i>
                                        <p>Aucune activité récente enregistrée.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Professors Tab -->
            <div class="tab-pane " id="professors" >
                <div class="card shadow-sm rounded-4 border-0 mt-4">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary fw-bold">
                            <i class="ti ti-users"></i> Liste des enseignants
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th><i class="ti ti-user"></i> Nom</th>
                                        <th><i class="ti ti-mail"></i> Email</th>
                                        <th><i class="ti ti-arrow-down-circle"></i> Min. Heures</th>
                                        <th><i class="ti ti-arrow-up-circle"></i> Max. Heures</th>
                                        <th><i class="ti ti-hourglass-low"></i> Assignées</th>
                                        <th><i class="ti ti-info-circle"></i> Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php foreach($professorStats as $prof): 
                                        $assigned = (int)$prof['assigned_hours'];
                                        $min = (int)$prof['min_hours'];
                                        $max = (int)$prof['max_hours'];
                                        if ($assigned < $min) {
                                            $badgeClass = 'bg-warning text-dark';
                                            $label = 'Sous-charge';
                                            $icon = 'ti ti-alert-triangle';
                                        } elseif ($assigned > $max) {
                                            $badgeClass = 'bg-danger text-white';
                                            $label = 'Surcharge';
                                            $icon = 'ti ti-arrow-big-up-line';
                                        } else {
                                            $badgeClass = 'bg-success text-white';
                                            $label = 'Optimal';
                                            $icon = 'ti ti-circle-check';
                                        }
                                    ?>
                                    <tr>
                                        <td class="fw-semibold text-primary"><?= htmlspecialchars($prof['firstName'] . ' ' . $prof['lastName']) ?></td>
                                        <td><?= htmlspecialchars($prof['email']) ?></td>
                                        <td><?= $min ?> h</td>
                                        <td><?= $max ?> h</td>
                                        <td class="fw-bold"><?= $assigned ?> h</td>
                                        <td>
                                            <span class="badge <?= $badgeClass ?> d-inline-flex align-items-center gap-1 px-3 py-2 rounded-pill shadow-sm">
                                                <i class="<?= $icon ?>"></i> <?= $label ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
<div class="tab-pane" id="modules">
    <!-- Module Statistics Cards -->
    <div class="module-stats-grid">
        <div class="stat-card module-stat">
            <div class="stat-icon bg-danger">
                <i class="ti ti-book text-white"></i>
            </div>
            <div class="stat-info text-danger">
                <h2 class="text-danger"><?= count($modules) ?></h2>
                <p>Modules Total</p>
            </div>
        </div>
        
        <div class="stat-card module-stat">
            <div class="stat-icon bg-success">
                <i class="ti ti-clock text-white"></i>
            </div>
            <div class="stat-info text-success">
                <h2  class="text-success"><?= array_sum(array_column($modules, 'volume_horaire')) ?> h</h2>
                <p>Volume Horaire Total</p>
            </div>
        </div>
        
        <?php
        // Count modules by semester
        $semesterCounts = [];
        foreach ($modules as $module) {
            $semester = strtoupper($module['semester'] ?? 'Unknown');
            if (!isset($semesterCounts[$semester])) {
                $semesterCounts[$semester] = 0;
            }
            $semesterCounts[$semester]++;
        }
        
        // Get the semester with most modules
        $maxSemester = !empty($semesterCounts) ? array_keys($semesterCounts, max($semesterCounts))[0] : 'Unknown';
        ?>
        
        <div class="stat-card module-stat">
            <div class="stat-icon orange bg-warning">
                <i class="ti ti-calendar text-white"></i>
            </div>
            <div class="stat-info text-warning">
                <h2 class="text-warning"><?= $maxSemester ?></h2>
                <p>Semestre le plus chargé</p>
            </div>
        </div>
        
        <?php
        // Count modules by department
        $departmentCounts = [];
        foreach ($modules as $module) {
            $dept = $module['filiere_name'] ?? 'Unknown';
            if (!isset($departmentCounts[$dept])) {
                $departmentCounts[$dept] = 0;
            }
            $departmentCounts[$dept]++;
        }
        ?>
        
        <div class="stat-card module-stat">
            <div class="stat-icon purple bg-primary">
                <i class="ti ti-building text-white"></i>
            </div>
            <div class="stat-info text-primary">
                <h2 class="text-primary"><?= count($departmentCounts) ?></h2>
                <p>Filières</p>
            </div>
        </div>
    </div>

    <!-- Module Management Card -->
    <div class="dashboard-card full-width">
        <div class="card-header ">
            <div>
                <h4>Gestion des Modules</h4>
                <p>Liste complète des modules par filière et semestre</p>
            </div>
        </div>
        
        <div class="card-body">
            <h5 class="fw-bold mb-3 text-primary">
                <i class="ti ti-filter"></i> Filtres des modules
            </h5>

            <div class="row g-3 align-items-center mb-3">
                <!-- Search Input -->
                <div class="col-md-4">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-primary border-end-0">
                            <i class="ti ti-search text-white"></i>
                        </span>
                        <input type="text" id="moduleSearch" class="form-control border-start-0" placeholder="Rechercher un module...">
                    </div>
                </div>

                <!-- Semester Filter -->
                <div class="col-md-3">
                    <select id="semesterFilter" class="form-select shadow-sm">
                        <option value="">Tous les semestres</option>
                        <?php
                        $semesters = array_unique(array_column($modules, 'semester'));
                        sort($semesters);
                        foreach ($semesters as $semester) {
                            echo '<option value="' . htmlspecialchars(strtoupper($semester)) . '">' . htmlspecialchars(strtoupper($semester)) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Filière Filter -->
                <div class="col-md-3">
                    <select id="departmentFilter" class="form-select shadow-sm">
                        <option value="">Toutes les filières</option>
                        <?php
                        $departments = array_unique(array_column($modules, 'filiere_name'));
                        sort($departments);
                        foreach ($departments as $department) {
                            echo '<option value="' . htmlspecialchars($department) . '">' . htmlspecialchars($department) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="col-md-2 text-end">
                    <button id="resetFilters" type="button" class="btn btn-outline-secondary w-100 shadow-sm">
                        <i class="ti ti-refresh"></i> Réinitialiser
                    </button>
                </div>

            </div>
            
            <!-- Modules Table -->
            <div class="modules-table-container">
                <table class="modules-table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="id">ID <i class="ti ti-arrow-down"></i></th>
                            <th class="sortable" data-sort="title">Titre <i class="ti ti-arrows-sort"></i></th>
                            <th>Description</th>
                            <th class="sortable" data-sort="hours">Heures <i class="ti ti-arrows-sort"></i></th>
                            <th class="sortable" data-sort="semester">Semestre <i class="ti ti-arrows-sort"></i></th>
                            <th class="sortable" data-sort="credits">Crédits <i class="ti ti-arrows-sort"></i></th>
                            <th class="sortable" data-sort="department">Filière <i class="ti ti-arrows-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($modules)): ?>
                            <?php foreach ($modules as $module): ?>
                                <tr class="module-row" 
                                    data-id="<?= $module['id_module'] ?>"
                                    data-title="<?= htmlspecialchars($module['title']) ?>"
                                    data-hours="<?= $module['volume_horaire'] ?>"
                                    data-semester="<?= strtoupper($module['semester']) ?>"
                                    data-credits="<?= $module['credits'] ?>"
                                    data-department="<?= htmlspecialchars($module['filiere_name']) ?>">
                                    <td><?= $module['id_module'] ?></td>
                                    <td class="module-title"><?= htmlspecialchars($module['title']) ?></td>
                                    <td class="module-description">
                                        <div class="description-truncate">
                                            <?= htmlspecialchars($module['description']) ?>
                                        </div>
                                    </td>
                                    <td><?= $module['volume_horaire'] ?> h</td>
                                    <td>
                                        <span class="semester-badge <?= strtolower($module['semester']) ?>">
                                            <?= strtoupper($module['semester']) ?>
                                        </span>
                                    </td>
                                    <td><?= $module['credits'] ?></td>
                                    <td><?= htmlspecialchars($module['filiere_name']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-table">
                                    <div class="empty-state">
                                        <i class="ti ti-book-off"></i>
                                        <p>Aucun module trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Affichage de <span id="startRange">1</span> à <span id="endRange"><?= min(count($modules), 10) ?></span> sur <span id="totalItems"><?= count($modules) ?></span> modules
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" disabled>
                        <i class="ti ti-chevron-left"></i>
                    </button>
                    <div class="pagination-pages">
                        <button class="pagination-page active">1</button>
                        <?php if (count($modules) > 10): ?>
                            <button class="pagination-page">2</button>
                        <?php endif; ?>
                        <?php if (count($modules) > 20): ?>
                            <button class="pagination-page">3</button>
                        <?php endif; ?>
                    </div>
                    <button class="pagination-btn" <?= count($modules) <= 10 ? 'disabled' : '' ?>>
                        <i class="ti ti-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
         
            <!-- Reports Tab (Hidden by Default) -->
            <div class="tab-pane" id="reports">
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h4>Génération de Rapports</h4>
                    </div>
                    <div class="card-body centered">
                        <div class="placeholder-content">
                            <i class="ti ti-chart-dots"></i>
                            <p>Contenu des outils de rapport ici.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal" id="taskModal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Ajouter une Nouvelle Tâche</h4>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="taskForm">
                <div class="form-group">
                    <label for="taskTitle">Titre</label>
                    <input type="text" id="taskTitle" placeholder="Ex: Préparer la réunion...">
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label for="taskDueDate">Échéance</label>
                        <input type="date" id="taskDueDate">
                    </div>
                    <div class="form-group half">
                        <label for="taskPriority">Priorité</label>
                        <select id="taskPriority">
                            <option value="low">Basse</option>
                            <option value="medium">Moyenne</option>
                            <option value="high">Haute</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="taskDescription">Description (Optionnel)</label>
                    <textarea id="taskDescription" rows="2" placeholder="Ajouter des détails..."></textarea>
                </div>
                <div class="form-group">
                    <label for="taskAssignee">Assigner à</label>
                    <select id="taskAssignee">
                        <option value="self">Moi-même</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel">Annuler</button>
            <button class="btn-save">Enregistrer</button>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Tab Switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabBtns.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Modal Functionality
    const addTaskBtn = document.querySelector('.btn-add-task');
    const modal = document.getElementById('taskModal');
    const closeBtn = document.querySelector('.modal-close');
    const cancelBtn = document.querySelector('.btn-cancel');
    
    if (addTaskBtn && modal) {
        addTaskBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });
    }
    
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    if (cancelBtn && modal) {
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Make sure Chart.js is loaded before initializing charts
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    } else {
        // Load Chart.js dynamically if not available
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.onload = initializeCharts;
        document.head.appendChild(script);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Module search functionality
    const moduleSearch = document.getElementById('moduleSearch');
    const semesterFilter = document.getElementById('semesterFilter');
    const departmentFilter = document.getElementById('departmentFilter');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const moduleRows = document.querySelectorAll('.module-row');
    
    // Function to filter modules
    function filterModules() {
        const searchTerm = moduleSearch.value.toLowerCase();
        const semesterValue = semesterFilter.value;
        const departmentValue = departmentFilter.value;
        
        let visibleCount = 0;
        
        moduleRows.forEach(row => {
            const title = row.getAttribute('data-title').toLowerCase();
            const semester = row.getAttribute('data-semester');
            const department = row.getAttribute('data-department');
            
            const matchesSearch = title.includes(searchTerm);
            const matchesSemester = !semesterValue || semester === semesterValue;
            const matchesDepartment = !departmentValue || department === departmentValue;
            
            const isVisible = matchesSearch && matchesSemester && matchesDepartment;
            row.style.display = isVisible ? '' : 'none';
            
            if (isVisible) visibleCount++;
        });
        
        // Update pagination info
        document.getElementById('startRange').textContent = visibleCount > 0 ? '1' : '0';
        document.getElementById('endRange').textContent = Math.min(visibleCount, 10);
        document.getElementById('totalItems').textContent = visibleCount;
        
        // Disable pagination if not enough items
        const nextPageBtn = document.querySelector('.pagination-btn:last-child');
        if (nextPageBtn) {
            nextPageBtn.disabled = visibleCount <= 10;
        }
        
        // Show empty state if no results
        const tbody = document.querySelector('.modules-table tbody');
        const existingEmptyRow = document.querySelector('.empty-results-row');
        
        if (visibleCount === 0 && !existingEmptyRow) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'empty-results-row';
            emptyRow.innerHTML = `
                <td colspan="8" class="empty-table">
                    <div class="empty-state">
                        <i class="ti ti-search-off"></i>
                        <p>Aucun module ne correspond à votre recherche</p>
                    </div>
                </td>
            `;
            tbody.appendChild(emptyRow);
        } else if (visibleCount > 0 && existingEmptyRow) {
            existingEmptyRow.remove();
        }
    }
    
    // Add event listeners
    if (moduleSearch) moduleSearch.addEventListener('input', filterModules);
    if (semesterFilter) semesterFilter.addEventListener('change', filterModules);
    if (departmentFilter) departmentFilter.addEventListener('change', filterModules);
    
    // Reset filters
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            moduleSearch.value = '';
            semesterFilter.value = '';
            departmentFilter.value = '';
            filterModules();
        });
    }
    
    // Sortable columns
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sortBy = this.getAttribute('data-sort');
            const currentIcon = this.querySelector('i');
            const isAscending = currentIcon.classList.contains('ti-arrow-up');
            
            // Reset all icons
            sortableHeaders.forEach(h => {
                const icon = h.querySelector('i');
                icon.className = 'ti ti-arrows-sort';
            });
            
            // Set current icon
            currentIcon.className = isAscending ? 'ti ti-arrow-down' : 'ti ti-arrow-up';
            
            // Sort the rows
            const tbody = document.querySelector('.modules-table tbody');
            const rows = Array.from(document.querySelectorAll('.module-row'));
            
            rows.sort((a, b) => {
                let valueA = a.getAttribute(`data-${sortBy}`);
                let valueB = b.getAttribute(`data-${sortBy}`);
                
                // Handle numeric values
                if (sortBy === 'id' || sortBy === 'hours' || sortBy === 'credits') {
                    valueA = parseInt(valueA);
                    valueB = parseInt(valueB);
                }
                
                if (isAscending) {
                    return valueA > valueB ? -1 : 1;
                } else {
                    return valueA < valueB ? -1 : 1;
                }
            });
            
            // Reorder the rows
            rows.forEach(row => {
                tbody.appendChild(row);
            });
        });
    });
    
    // Pagination functionality
    const paginationPages = document.querySelectorAll('.pagination-page');
    const prevPageBtn = document.querySelector('.pagination-btn:first-child');
    const nextPageBtn = document.querySelector('.pagination-btn:last-child');
    
    if (paginationPages.length > 0) {
        paginationPages.forEach((page, index) => {
            page.addEventListener('click', function() {
                // Update active page
                paginationPages.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                
                // Enable/disable prev/next buttons
                if (prevPageBtn) prevPageBtn.disabled = index === 0;
                if (nextPageBtn) nextPageBtn.disabled = index === paginationPages.length - 1;
                
                // Update visible range
                const pageNum = parseInt(this.textContent);
                const startItem = (pageNum - 1) * 10 + 1;
                const totalItems = parseInt(document.getElementById('totalItems').textContent);
                const endItem = Math.min(pageNum * 10, totalItems);
                
                document.getElementById('startRange').textContent = startItem;
                document.getElementById('endRange').textContent = endItem;
                
                // TODO: In a real implementation, this would load the next page of data
                // For this demo, we'll just hide/show rows
                const visibleRows = Array.from(document.querySelectorAll('.module-row')).filter(row => 
                    row.style.display !== 'none'
                );
                
                visibleRows.forEach((row, i) => {
                    const shouldShow = i >= (pageNum - 1) * 10 && i < pageNum * 10;
                    row.style.display = shouldShow ? '' : 'none';
                });
            });
        });
        
        // Previous page button
        if (prevPageBtn) {
            prevPageBtn.addEventListener('click', function() {
                if (this.disabled) return;
                
                const activePage = document.querySelector('.pagination-page.active');
                const prevPage = activePage.previousElementSibling;
                if (prevPage) prevPage.click();
            });
        }
        
        // Next page button
        if (nextPageBtn) {
            nextPageBtn.addEventListener('click', function() {
                if (this.disabled) return;
                
                const activePage = document.querySelector('.pagination-page.active');
                const nextPage = activePage.nextElementSibling;
                if (nextPage) nextPage.click();
            });
        }
    }
});
function initializeCharts() {
    try {
        // Workload Distribution Chart
        const workloadCtx = document.getElementById('workloadChart');
        if (workloadCtx) {
            // Get data from PHP or use default if not available
            let workloadData = [];
            try {
                workloadData = <?= $workloadJson ?> || [];
            } catch (e) {
                console.error('Error parsing workload data:', e);
                workloadData = [
                    {status: "Dépassée", total: 1},
                    {status: "Insuffisante", total: 1},
                    {status: "Correct", total: 1}
                ];
            }
            
            const labels = workloadData.map(item => item.status || 'Inconnu');
            const data = workloadData.map(item => item.total || 0);
            const colors = [
                '#4361ee', // Primary
                '#fa896b',  // Danger
                '#00c853' //success
            ];
            
            new Chart(workloadCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Professors',
                        data: data,
                        backgroundColor: colors,
                        borderRadius: 6,
                        maxBarThickness: 50
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#2a3547',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: '#eaecef'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#939ea5'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#939ea5'
                            }
                        }
                    }
                }
            });
        }
        
        // Validation Timeline Chart
        const validationCtx = document.getElementById('validationChart');
        if (validationCtx) {
            // Get data from PHP or use default if not available
            let validationStatsData = [];
            try {
                validationStatsData = <?= $validationStatsJson ?> || [];
            } catch (e) {
                console.error('Error parsing validation stats data:', e);
                validationStatsData = [
                    {year: 2025, validated: 2},
                    {year: 2024, validated: 1}
                ];
            }
            
            const years = validationStatsData.map(item => item.year || '');
            const validatedCounts = validationStatsData.map(item => item.validated || 0);
            
            new Chart(validationCtx, {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{
                        label: 'Modules Validés',
                        data: validatedCounts,
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4361ee',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#2a3547',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: '#eaecef'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#939ea5'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#939ea5'
                            }
                        }
                    }
                }
            });
        }
        
        // Module Status Chart (Donut)
        const moduleStatusCtx = document.getElementById('moduleStatusChart');
        const moduleStatusLegend = document.getElementById('moduleStatusLegend');
        
        if (moduleStatusCtx) {
            // Get data from PHP or use default if not available
            let moduleChoicesData = [];
            try {
                moduleChoicesData = <?= $moduleChoicesJson ?> || [];
            } catch (e) {
                console.error('Error parsing module choices data:', e);
                moduleChoicesData = [
                    {status: "validated", count: 3},
                    {status: "declined", count: 3},
                    {status: "in progress", count: 1}
                ];
            }
            
            // Extract data for the donut chart
            const statuses = moduleChoicesData.map(item => {
                if (item.status === 'validated') return 'Validés';
                if (item.status === 'declined') return 'Refusés';
                if (item.status === 'in progress') return 'En cours';
                return item.status;
            });
            
            const counts = moduleChoicesData.map(item => item.count || 0);
            
            const colors = [
                '#00c853', // Success - validated
                '#fa896b', // Danger - declined
                '#ffae1f'  // Warning - in progress
            ];
            
            // Create chart
            new Chart(moduleStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: statuses,
                    datasets: [{
                        data: counts,
                        backgroundColor: colors,
                        borderWidth: 4,
                        borderColor: '#ffffff',
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#2a3547',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false
                        }
                    }
                }
            });
            
            // Generate custom legend
            if (moduleStatusLegend) {
                moduleStatusLegend.innerHTML = '';
                moduleChoicesData.forEach((item, index) => {
                    let label = item.status;
                    if (label === 'validated') label = 'Validés';
                    if (label === 'declined') label = 'Refusés';
                    if (label === 'in progress') label = 'En cours';
                    
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';
                    legendItem.style.display = 'flex';
                    legendItem.style.alignItems = 'center';
                    legendItem.style.marginBottom = '8px';
                    legendItem.innerHTML = `
                        <span style="display:inline-block; width:12px; height:12px; background-color:${colors[index]}; margin-right:8px; border-radius:2px;"></span>
                        <span>${label} (${item.count})</span>
                    `;
                    moduleStatusLegend.appendChild(legendItem);
                });
            }
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
        // Add fallback content for charts
        document.querySelectorAll('.chart-container, .donut-chart-container').forEach(container => {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="ti ti-chart-bar-off"></i>
                    <p>Impossible de charger le graphique</p>
                </div>
            `;
        });
    }
}
</script>
<?php
    return ob_get_clean();
}
?>
