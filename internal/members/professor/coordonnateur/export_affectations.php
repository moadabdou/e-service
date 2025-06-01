<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";

session_start();

$userId = $_SESSION['id_user'] ?? null;
if (!$userId) {
    http_response_code(403);
    exit("Accès refusé");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="affectations_filiere.csv"');

$moduleModel = new ModuleModel();
$filiereModel = new FiliereModel();
$userModel = new UserModel();

$filiereId = $filiereModel->getFiliereIdByCoordinator($userId);

if (!$filiereId) {
    echo "Aucune filière trouvée pour ce coordonnateur.";
    exit;
}

$year = date('Y');
$groupedAssignments = $moduleModel->getAssignmentsByFiliereAndYearGrouped($filiereId, $year);

$output = fopen("php://output", "w");

fputcsv($output, ["Code Module", "Titre Module", "Professeur", "Semestre", "Crédits"]);

foreach ($groupedAssignments as $sem => $assignments) {
    foreach ($assignments as $assign) {
        $profName = $userModel->getFullNameById($assign['id_professor']) ?? '---';
        fputcsv($output, [
            $assign['code_module'],
            $assign['title'],
            $profName,
            strtoupper($assign['semester']),
            $assign['credits']
        ]);
    }
}

fclose($output);
exit;
