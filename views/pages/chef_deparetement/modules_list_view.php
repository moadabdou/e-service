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
                ?>
                    <div class="col-12 col-md-6 col-lg-4 filterable-item"
                        data-semester="<?= htmlspecialchars($semesterValue) ?>"
                        data-filiere="<?= htmlspecialchars($filiereValue) ?>">
                        <article class="card h-100 shadow rounded-4 border-0 overflow-hidden hover-shadow transition-300 mb-3">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title text-primary fw-bold mb-3">
                                        <?= htmlspecialchars($module['title']) ?>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="ti ti-list-details" aria-hidden="true"></i> 
                                        <?= htmlspecialchars($module['description'] ?? 'Aucune description') ?>
                                    </p>
                                    <ul class="list-unstyled mb-3">
                                        <li><strong>Volume horaire :</strong> <?= htmlspecialchars($module['volume_horaire']) ?> heures</li>
                                        <li><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></li>
                                        <li><strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name'] ?? '') ?></li>
                                    </ul>
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