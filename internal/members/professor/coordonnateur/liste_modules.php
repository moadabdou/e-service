<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$coordinatorId = $_SESSION['id_user'] ?? null;

$modules = [];

if ($coordinatorId) {
    $filiereModel = new FiliereModel();
    $filiereId = $filiereModel->getFiliereIdByCoordinatorUserId($coordinatorId);;

    if ($filiereId) {
        $moduleModel = new ModuleModel();
        $modules = $moduleModel->getModulesByFiliereId($filiereId);
    }
}

ob_start();
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Modules de la filière</h5>

        <?php if (!empty($modules)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Volume horaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?= htmlspecialchars($module['id_module']) ?></td>
                            <td><?= htmlspecialchars($module['title']) ?></td>
                            <td><?= htmlspecialchars($module['volume_horaire']) ?>h</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Aucun module trouvé pour votre filière.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "ModuleListing", $content);
?>