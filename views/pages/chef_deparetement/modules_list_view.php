<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php"; 

function chefDepModulesListView(array $modules, array $filliers): string {
    ob_start();
    ?>
    <div class="container mt-2 px-4 px-md-5 mb-4">
        <header class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h2 class="fw-bold text-primary mb-2 mb-md-0">
                <i class="ti ti-books" aria-hidden="true"></i> Unités d'enseignement du département
            </h2>
        </header>
    
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
                ]
            ],
            "listContainer",
            "filterable-item",
            "itemCount"
        ); ?>

        <?php if (empty($modules)) : ?>
            <div class="alert alert-warning text-center shadow-sm">
                <i class="ti ti-alert-circle" aria-hidden="true"></i> Aucun module disponible pour le moment.
            </div>
        <?php else : ?>
            <div class="row g-4" id="listContainer">
                <?php foreach ($modules as $module) : 
                    $semesterValue = strtolower($module['semester'] ?? '');
                    $filiereValue = strtolower(str_replace(' ', '_', $module['filiere_name'] ?? ''));
                    
                    // Format evaluation type
                    $Type = '';
                    
                    if ($module['evaluation'] == 0) :
                        $Type       = 'Module indépendant';
                        $evaluation = 6;
                    else :
                        $Type       = 'Élément de module';
                        $evaluation = 3;
                    endif;
                    
                    
                    
                    
                    // Calculate total volume
                    $totalVolume = ($module['volume_cours'] ?? 0) + 
                                  ($module['volume_td'] ?? 0) + 
                                  ($module['volume_tp'] ?? 0) + 
                                  ($module['volume_autre'] ?? 0);
                ?>

                <div class="col-12 col-md-6 col-lg-4 filterable-item"
                    data-semester="<?= htmlspecialchars($semesterValue) ?>"
                    data-filiere="<?= htmlspecialchars($filiereValue) ?>">
                <article class="card h-100 shadow rounded-4 border-0 overflow-hidden hover-shadow transition-300 mb-3">
                    <div class="card-header bg-primary bg-opacity-10 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-primary fw-bold mb-0">
                        <?= htmlspecialchars($module['title']) ?>
                        </h5>
                        <span class="badge bg-primary"><?= htmlspecialchars($module['code_module'] ?? 'N/A') ?></span>
                    </div>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between p-3">
                    <div>
                        <p class="text-muted mb-3">
                        <i class="ti ti-list-details" aria-hidden="true"></i> 
                        <?= htmlspecialchars($module['description'] ?? 'Aucune description') ?>
                        </p>

                        <h6 class="fw-bold text-primary"><i class="ti ti-info-circle"></i> Informations générales</h6>
                        <ul class="list-unstyled mb-3">
                        <li><i class="ti ti-calendar me-2"></i><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></li>
                        <li><i class="ti ti-certificate me-2"></i><strong>Crédits :</strong> <?= htmlspecialchars($module['credits'] ?? '0') ?> Cré</li>
                        <li><i class="ti ti-book me-2"></i><strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name'] ?? '') ?></li>
                        <li><i class="ti ti-clipboard-check me-2"></i><strong>Type :</strong> <?= $Type ?></li>
                        <li><i class="ti ti-award  me-2"></i><strong>Évaluation :</strong> <?= $evaluation ?></li>
                        </ul>

                        <h6 class="fw-bold text-primary mb-3"><i class="ti ti-clock"></i> Volume horaire : <strong><?= $totalVolume ?></strong> heurs</h6>
                        <div class="bg-light p-3 rounded-3 mb-2">

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
                    </div>

                    </div>
                </article>
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
    /* Card-wrapped modern table */
.modern-table-card {
  border: none;
  border-radius: 8px;
  overflow: hidden;
}

/* Modern table base */
.table-modern {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

/* Header styling */
.table-modern thead {
  background-color: #f5f7fa;
}
.table-modern thead th {
  color: #374151;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  border-bottom: none;
}

/* Body rows */
.table-modern tbody tr {
  background-color: #ffffff;
  transition: background-color 0.2s;
}
.table-modern tbody tr:hover {
  background-color: #f0f4f8;
}

/* Cells */
.table-modern td {
  color: #4b5563;
  font-size: 0.9rem;
  padding: 0.75rem 1rem;
  border-top: none;
}

/* Bold total cell */
.table-modern td.fw-bold {
  color: #111827;
}

/* Subtle column dividers */
.table-modern th + th,
.table-modern td + td {
  border-left: 1px solid rgba(0, 0, 0, 0.05);
}

    
</style>
    <?php
    
    return ob_get_clean();
}