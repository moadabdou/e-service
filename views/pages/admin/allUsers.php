<?php 
require_once __DIR__."../../../layouts/mini_navbar/minNavbar.php";
require_once __DIR__."../../../components/simple_user_card/simple_user_card.php";

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

        $roles = ["professeurs", "chefs des départements", "coordinateurs", "vacataires", "administrateurs"];

        foreach($roles as $index => &$role){

            $role  = [$role, $base_url.(string)$index];

        }

        $filters = (new MiniNav($roles , active: $type))->view();


        $users = "";
        $user_card = new SimpleUserCard();

        if (empty($users_data)){

            $users = "<h4 class='text-center'>aucun utilisateur trouvé</h4>";

        }else {
            
            foreach($users_data as  $user_data){

                $users .=  $user_card->view($user_data["id_user"],$user_data["img"], implode(" ", [$user_data["firstName"], $user_data["lastName"]]) , $user_data["creation_date"]);

            }


        }


        ob_start();
        require __DIR__."/allUsers.view.php";
        $content = ob_get_clean();
        return $content;

    }

}

?>