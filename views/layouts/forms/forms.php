<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";

class Form{

    public function professorFormView(array $errors = [], ?array $info){

        
        $depModel = new DepartementModel();

        $departements = $depModel->getAll();

        ob_start();
        require __DIR__."/professor_form.view.php";
        $role_based_fields = ob_get_clean();
    
        ob_start();
        require __DIR__."/base_form.view.php";
        return ob_get_clean();
    }

}

?>