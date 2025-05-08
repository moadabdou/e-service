<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$filiereModel = new FiliereModel();
$moduleModel = new ModuleModel();
$userModel = new UserModel();

$coordinatorId = $_SESSION['id_user'];

$filiereId = null;
$modules = [];

if ($coordinatorId) {
    $filiereId = $filiereModel->getFiliereIdByCoordinator($coordinatorId);

    if ($filiereId) {
        $modules = $moduleModel->getModulesByFiliereId($filiereId);
    }
}

ob_start();
?>
<div class="container px-4">
    <div class="row">
        <div class="col">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">
                        <i class="ti ti-list-details text-primary me-2"></i>
                        Liste des Modules de la Filière
                    </h4>

                    <?php if (!empty($modules)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Semestre</th>
                                        <th>Volume Total</th>
                                        <th>Crédits</th>
                                        <th>Responsable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($modules as $module): ?>
                                        <?php
                                        $totalVolume = $module['volume_cours'] + $module['volume_td'] + $module['volume_tp'] + $module['volume_autre'];
                                        $type = "Complet";
                                        if ((int)$module['evaluation'] !== 0) {
                                            if (str_contains($module['code_module'], '.')) {
                                                $type = "Sous-module";
                                            } else {
                                                $type = "Parent";
                                            }
                                        }
                                        $responsableName = $userModel->getFullNameById($module[''] ?? 0) ?? '---';
                                        ?>
                                        <tr>
                                            <td class="fw-medium text-primary-emphasis"><?= htmlspecialchars($module['code_module']) ?></td>
                                            <td><?= htmlspecialchars($module['title']) ?></td>
                                            <td>
                                                <span class="badge rounded-pill bg-<?= $type === 'Complet' ? 'success' : ($type === 'Parent' ? 'info' : 'warning') ?>">
                                                    <?= $type ?>
                                                </span>
                                            </td>
                                            <td><?= strtoupper(htmlspecialchars($module['semester'])) ?></td>
                                            <td><?= $totalVolume ?>h</td>
                                            <td><?= htmlspecialchars($module['credits']) ?></td>
                                            <td><?= htmlspecialchars($responsableName) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info rounded-4 mt-3">
                            Aucun module trouvé pour votre filière.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "ModuleListing", $content);
