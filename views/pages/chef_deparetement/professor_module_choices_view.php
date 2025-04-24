<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function professorModuleChoicesView(array $professors): string {
    ob_start();
?>
<div class="container mt-2 px-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="ti ti-list-check"></i> Choix des professeurs
        </h2>
    </div>

    <?= createSearchFilterComponent(
        "Rechercher un professeur...",
        [

            "status" => [
                "label" => "Statut de charge",
                "icon" => "ti-info-circle",
                "allLabel" => "Tous les statuts",
                "options" => [
                    ["value" => "correcte", "label" => "Correcte"],
                    ["value" => "insuffisante", "label" => "Insuffisante"],
                    ["value" => "depassee", "label" => "Dépassée"]
                ]
            ]
        ],
        "professorCards",
        "professor-card",
        "professorCount"
    ) ?>

    <div class="row g-4" id="professorCards">
        <?php foreach ($professors as $prof): 
            $assigned = (int) $prof['assigned_hours'];
            $min = (int) $prof['min_hours'];
            $max = (int) $prof['max_hours'];

            if ($assigned < $min) {
                $statusClass = 'border-warning bg-warning-subtle';
                $statusLabel = 'insuffisante';
                $statusBadge = '<span class="badge bg-warning text-dark"><i class="ti ti-alert-triangle"></i> Insuffisante</span>';
            } elseif ($assigned > $max) {
                $statusClass = 'border-danger bg-danger-subtle';
                $statusLabel = 'depassee';
                $statusBadge = '<span class="badge bg-danger text-white"><i class="ti ti-arrow-big-up-line"></i> Dépassée</span>';
            } else {
                $statusClass = 'border-success bg-success-subtle';
                $statusLabel = 'correcte';
                $statusBadge = '<span class="badge bg-success text-white"><i class="ti ti-circle-check"></i> Correcte</span>';
            }
        ?>
        <div class="col-12 col-md-6 col-lg-4 professor-card mb-3"
             data-status="<?= $statusLabel ?>">
            <div class="card shadow h-100 rounded-4 border-2 hover-shadow transition-300 <?= $statusClass ?>">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="<?= "/e-service/storage/image/users_pp/" . htmlspecialchars($prof['img'] ?? 'default.webp') ?>"
                                 class="rounded-circle  border-2"
                                 width="60" height="60" alt="avatar">
                            <div>
                                <h5 class="mb-0 fw-bold text-primary"><?= htmlspecialchars($prof['firstName'] . " " . $prof['lastName']) ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($prof['email']) ?></small>
                            </div>
                        </div>
                        <p class="mb-1"><strong>Charge :</strong> <?= $assigned ?>h (min: <?= $min ?>h / max: <?= $max ?>h)</p>
                        <p class="mb-2"><?= $statusBadge ?></p>
                        <p class="mb-1"><strong>Modules choisis :</strong></p>
                        <?php
                        $groupedModules = [];

                        foreach ($prof['modules'] as $module) {
                            $groupedModules[$module['filiere']][] = $module['title'];
                        }
                        ?>

                        <?php foreach ($groupedModules as $filiere => $modules): ?>
                            <p class="fw-semibold text-secondary mb-1"><?= htmlspecialchars($filiere) ?> :</p>
                            <ul class="mb-2">
                                <?php foreach ($modules as $title): ?>
                                    <li><i class="ti ti-book"></i> <?= htmlspecialchars($title) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>

                    </div>
                    <div class="text-center">
                        <a href="/e-service/internal/members/common/view_profile.php?id=<?= $prof['id_user'] ?>"
                           class="btn btn-outline-primary w-100">
                            <i class="ti ti-user-circle me-1"></i> Voir profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<style>
    .transition-300 {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
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
?>
