<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/resource_loader/resource_loader.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority([]);


$BASE_RESOURCE_PATH = $_SERVER['DOCUMENT_ROOT']."/e-service/storage/";

$type = isset($_REQUEST["type"])? $_REQUEST["type"] : "unknown";
$path = isset($_REQUEST["path"])? 
                $BASE_RESOURCE_PATH.$type."/".$_REQUEST["path"] : 
                "unknown";

try {

    ResourceLoader::sendToBrowser($type , $path);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



?>