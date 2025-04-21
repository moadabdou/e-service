<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php"; // if it's not already included

function chefDepProfessorsListView(array $professors): string {
    ob_start();
?>
<div class="container mt-5 px-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-primary mb-2 mb-md-0">
            <i class="ti ti-users"></i> Enseignants du département
        </h2>
    </div>

    <?= createSearchFilterComponent(
        "Rechercher un enseignant...",
        [
            "role" => [
                "label" => "Rôle",
                "icon" => "ti-user",
                "allLabel" => "Tous les rôles",
                "options" => [
                    ["label" => "Chef de Département", "value" => "chef_deparetement"],
                    ["label" => "Coordonnateur", "value" => "coordonnateur"],
                    ["label" => "Normal", "value" => "normal"]
                ]
            ]
        ],
        "profList",
        "prof-card",
    ) ?>

    <?php if (empty($professors)) : ?>
        <div class="alert alert-warning text-center shadow-sm">
            <i class="ti ti-alert-circle"></i> Aucun enseignant disponible pour le moment.
        </div>
    <?php else : ?>
        <div class="row g-4" id="profList">
            <?php foreach ($professors as $professor) : ?>
                <div class="col-12 col-md-6 col-lg-4 prof-card" data-role="<?= htmlspecialchars($professor['p_role']) ?>">
                    <div class="card h-100 shadow rounded-4 border-0">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-primary fw-bold mb-3">
                                    <?= htmlspecialchars($professor['firstName'] . ' ' . $professor['lastName']) ?>
                                </h5>
                                <p class="text-muted mb-2">
                                    <i class="ti ti-id"></i> <?= htmlspecialchars($professor['CIN']) ?>
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="ti ti-mail"></i> <?= htmlspecialchars($professor['email']) ?>
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="ti ti-phone"></i> <?= htmlspecialchars($professor['phone'] ?? 'Non spécifié') ?>
                                </p>
                                <ul class="list-unstyled mb-3">
                                    <li><strong>Rôle :</strong> Un <?= htmlspecialchars($professor['u_role']." ".$professor['p_role'] ?? 'Enseignant') ?></li>
                                    <li><strong>Charge horaire :</strong> <?= htmlspecialchars($professor['min_hours'] ?? '0') ?>h - <?= htmlspecialchars($professor['max_hours'] ?? '0') ?>h</li>
                                </ul>
                            </div>
                            <div class="text-center">
                                <a href="#" class="btn btn-outline-primary d-flex align-items-center gap-2 shadow-sm">
                                    <i class="ti ti-user-circle"></i> Voir profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
    return ob_get_clean();
}
?>
