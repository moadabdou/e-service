<?php 

class DepartementView{

    public function view(array $dep_data, mixed $head_data){

        ob_start();
        require __DIR__."/view_departement.view.php";
        $content = ob_get_clean();
        return $content;

    }


}



?>