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
<div class="container mt-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="ti ti-list-details"></i> Liste des Modules de la Filière
    </h3>

    <?php if (!empty($modules)) : ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Semestre</th>
                        <th>Volume Total</th>
                        <th>Crédits</th>
                        <th>Responsable</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module) :
                        $volume_total = ($module['volume_cours'] ?? 0) + ($module['volume_td'] ?? 0) + ($module['volume_tp'] ?? 0) + ($module['volume_autre'] ?? 0);
                        $responsableName = $userModel->getFullNameById($module['responsable'] ?? 0) ?? '---';
                        $type = 'Complet';
                        if ($module['evaluation'] != 0) {
                            $type = strpos($module['code_module'], '.') !== false ? 'Sous-module' : 'Parent';
                        }
                        $badgeClass = [
                            'Complet' => 'bg-success',
                            'Parent' => 'bg-info',
                            'Sous-module' => 'bg-warning'
                        ];
                        $collapseId = 'details' . $module['id_module'];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($module['code_module']) ?></td>
                            <td><?= htmlspecialchars($module['title']) ?></td>
                            <td><span class="badge <?= $badgeClass[$type] ?> text-white"><?= $type ?></span></td>
                            <td><?= htmlspecialchars($module['semester']) ?></td>
                            <td><?= $volume_total ?> h</td>
                            <td><?= htmlspecialchars($module['credits']) ?></td>
                            <td><?= htmlspecialchars($responsableName) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                                    Détails
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="<?= $collapseId ?>">
                            <td colspan="8" class="bg-light">
                                <strong>Description:</strong> <?= nl2br(htmlspecialchars($module['description'] ?? '---')) ?><br>
                                <strong>Volume Cours:</strong> <?= $module['volume_cours'] ?> h |
                                <strong>TD:</strong> <?= $module['volume_td'] ?> h |
                                <strong>TP:</strong> <?= $module['volume_tp'] ?> h |
                                <strong>Autre:</strong> <?= $module['volume_autre'] ?> h<br>
                                <strong>Spécialité:</strong> <?= htmlspecialchars($specialityModel->getTitleById($module['id_speciality'] ?? 0) ?? '---') ?><br>
                                <strong>Code Module:</strong> <?= htmlspecialchars($module['code_module']) ?> |
                                <strong>Évaluation ID:</strong> <?= htmlspecialchars($module['evaluation']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-warning">Aucun module trouvé pour votre filière.</div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "ModuleListing", $content);
