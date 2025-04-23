<?php 
class DepartementList{

    public function view(array $data){

        ob_start();
        require __DIR__."/departement_list.view.php";
        $content = ob_get_clean();
        return $content;

    }


}

?>