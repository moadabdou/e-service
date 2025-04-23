<?php 
class DepartementForm{

    public function view(?array $info){

        ob_start();
        require __DIR__."/departement_form.view.php";
        $content = ob_get_clean();
        return $content;

    }


}

?>