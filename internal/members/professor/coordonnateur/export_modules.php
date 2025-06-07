<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
session_start();

// Get modules by coordinator
$moduleModel = new ModuleModel();
$filiereModel = new FiliereModel();

$coordId = $_SESSION['id_user'] ?? null;
$filiereId = $filiereModel->getFiliereIdByCoordinator($coordId);
$modules = $moduleModel->getModulesByFiliereId($filiereId);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="modules.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Code', 'Titre', 'Semestre', 'Volume Cours', 'TD', 'TP', 'Autre', 'Crédits']);

foreach ($modules as $mod) {
    fputcsv($output, [
        $mod['code_module'],
        $mod['title'],
        $mod['semester'],
        $mod['volume_cours'],
        $mod['volume_td'],
        $mod['volume_tp'],
        $mod['volume_autre'],
        $mod['credits']
    ]);
}
fclose($output);
exit;
