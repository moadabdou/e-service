<?php 
require_once __DIR__."../../../layouts/mini_navbar/minNavbar.php";

class DepartementsView{

    public function view(string $content, int $active){


        $base_url = "/e-service/internal/members/admin/Departements.php?filter=";

        $roles = ["tous les départements", "ajouter un département"];

        foreach($roles as $index => &$role){

            $role  = [$role, $base_url.(string)$index];

        }

        $filters = (new MiniNav($roles , active: $active))->view();

        ob_start();
        require __DIR__."/departements.view.php";
        $content = ob_get_clean();
        return $content;
    }  


}

?>