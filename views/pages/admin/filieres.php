<?php 
require_once __DIR__."../../../layouts/mini_navbar/minNavbar.php";

class FilieresView{

    public function view(string $content, $departements, int $active, int $id_dep, $dep_data){




        $base_url = "/e-service/internal/members/admin/filieres.php?id_dep=".$id_dep."&filter=";

        $actions = ["tous les filières", "ajouter une filière"];

        foreach($actions as $index => &$role){

            $role  = [$role, $base_url.(string)$index];

        }

        $filters = (new MiniNav($actions , active: $active))->view();

        ob_start();
        require __DIR__."/filieres.view.php";
        $content = ob_get_clean();
        return $content;
    }  


}

?>