<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/auth/auth.php";
$auth = new Auth();




$auth->loginView([
    "password" => "password  is too  short"
]);

?>