<?php 
class FiliereForm{

    public function view(?array $info, int $dep_id){

        ob_start();
        require __DIR__."/filiere_form.view.php";
        $content = ob_get_clean();
        return $content;

    }


}

?>