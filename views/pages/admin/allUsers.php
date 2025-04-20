<?php 
require_once __DIR__."../../../layouts/mini_navbar/minNavbar.php";

class AllUsersView{

    /**
     * @param type 
     * type is the  type of users you  wanna  show  
     *  0 => professors
     *  1 => departement head  
     *  2 => coordinator 
     *  3 => vacataires 
     *  4 => admins 
     */

    public function view(int $type = 0, array $users_data){

        $base_url = "/e-service/internal/members/admin/AllUsers.php?filter=";

        $roles = ["professors", "deparetements heads","coordinators", "vacataires","admins"];

        foreach($roles as $index => &$role){

            $role  = [$role, $base_url.(string)$index];

        }

        $filters = (new MiniNav($roles , active: $type))->view();


        $users = "";


        ob_start();
        require __DIR__."/allUsers.view.php";
        $content = ob_get_clean();
        return $content;

    }

}

?>