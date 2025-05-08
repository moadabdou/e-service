<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/speciality.php";

class Form{

    public function professorFormView(array $errors = [], ?array $info){

        
        $specialityModel = new SpecialityModel();

        $specialities = $specialityModel->getAll();

        ob_start();
        require __DIR__."/professor_form.view.php";
        $role_based_fields = ob_get_clean();
    
        ob_start();
        require __DIR__."/base_form.view.php";
        return ob_get_clean();
    }

    public function vacataireFormView(array $errors = [], ?array $info){
        $specialityModel = new SpecialityModel();

        $specialities = $specialityModel->getAll();

        ob_start();
        require __DIR__."/vacataire_form.view.php";
        $role_based_fields = ob_get_clean();
    
        ob_start();
        require __DIR__."/base_form.view.php";
        return ob_get_clean();
    }

}

?>