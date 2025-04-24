<?php 
class FiliereList{

    public function view(array $data, int $dep_id){

        ob_start();
        require __DIR__."/filiere_list.view.php";
        $content = ob_get_clean();
        return $content;

    }


}

?>