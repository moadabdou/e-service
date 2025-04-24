<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function professorWorkloadView(array $workloads): string {
    ob_start();

    // Générer les statuts dynamiquement
    $statuses = [
        ["value" => "insuffisante", "label" => "Insuffisante"],
        ["value" => "correcte", "label" => "Correcte"],
        ["value" => "dépassée", "label" => "Dépassée"]
    ];
?>
<div class="container mt-3 px-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-calendar-stats"></i> Suivi de charge horaire des enseignants
        </h2>
    </div>

    <?= createSearchFilterComponent(
        "Rechercher un enseignant...",
        [
            "statut" => [
                "label" => "Statut",
                "icon" => "ti-info-circle",
                "allLabel" => "Tous les statuts",
                "options" => $statuses
            ]
        ],
        "workloadList",
        "workload-item",
        "workloadCount"
    ); ?>

    <?php if (empty($workloads)) : ?>
        <div class="alert alert-warning text-center shadow-sm">
            <i class="ti ti-alert-circle"></i> Aucun professeur trouvé pour le moment.
        </div>
    <?php else : ?>
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-0">
                <div class="table-responsive rounded-bottom">
                    <table class="table table-hover align-middle mb-0" id="workloadList">
                        <thead class="table-light text-center">
                            <tr>
                                <th><i class="ti ti-user"></i> Nom</th>
                                <th><i class="ti ti-mail"></i> Email</th>
                                <th><i class="ti ti-arrow-down-circle"></i> Min</th>
                                <th><i class="ti ti-arrow-up-circle"></i> Max</th>
                                <th><i class="ti ti-hourglass-low"></i> Affectée</th>
                                <th><i class="ti ti-info-circle"></i> Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workloads as $w): 
                                $status = '';
                                $class = '';
                                $statusFilterValue = '';

                                if ($w['assigned_hours'] < $w['min_hours']) {
                                    $status = 'Insuffisante';
                                    $class = 'bg-warning text-dark';
                                    $statusFilterValue = 'insuffisante';
                                } elseif ($w['assigned_hours'] > $w['max_hours']) {
                                    $status = 'Dépassée';
                                    $class = 'bg-danger text-white';
                                    $statusFilterValue = 'dépassée';
                                } else {
                                    $status = 'Correcte';
                                    $class = 'bg-success text-white';
                                    $statusFilterValue = 'correcte';
                                }
                            ?>
                            <tr class="text-center workload-item" data-statut="<?= $statusFilterValue ?>">
                                <td class="fw-semibold text-primary"><?= htmlspecialchars($w['firstName'] . ' ' . $w['lastName']) ?></td>
                                <td><?= htmlspecialchars($w['email']) ?></td>
                                <td><?= htmlspecialchars($w['min_hours']) ?>h</td>
                                <td><?= htmlspecialchars($w['max_hours']) ?>h</td>
                                <td class="fw-bold"><?= htmlspecialchars($w['assigned_hours']) ?>h</td>
                                <td>
                                    <span class="badge <?= $class ?> px-3 py-2 d-inline-flex align-items-center gap-1">
                                        <i class="ti <?= $status === 'Correcte' ? 'ti-circle-check' : ($status === 'Insuffisante' ? 'ti-alert-triangle' : 'ti-arrow-big-up-line') ?>"></i>
                                        <?= $status ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
    return ob_get_clean();
}
?>
