<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
$dashboard = new DashBoard();


ob_start();
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Sample Page</h5>
        <p class="mb-0">This is a sample page </p>
    </div>
</div>

<?php
$content = ob_get_clean();

$dashboard->view("Admin", "AllUsers", $content);

?>