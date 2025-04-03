<?php 
class Form{

    public function professorFormView(){

        $departements = [
            [
                "title" => "Math",
                "id_deparetement" => 1
            ],
            [
                "title" => "Physics",
                "id_deparetement" => 2
            ],
            [
                "title" => "Chemistry",
                "id_deparetement" => 3
            ],
            [
                "title" => "Biology",
                "id_deparetement" => 4
            ],
            [
                "title" => "Computer Science",
                "id_deparetement" => 5
            ],
            [
                "title" => "Engineering",
                "id_deparetement" => 6
            ],
        ];

        ob_start();
        require __DIR__."/professor_form.view.php";
        $role_based_fields = ob_get_clean();
    
        ob_start();
        require __DIR__."/base_form.view.php";
        return ob_get_clean();
    }

}

?>