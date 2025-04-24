<?php 

class FiliereView{

    public function view(array $filiere_data, mixed $coor_data){

        ob_start();
        require __DIR__."/view_filiere.view.php";
        $content = ob_get_clean();
        return $content;

    }


}



?>