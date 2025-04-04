<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/navigation/navigation.php";

class UserController{

    /**
     * this function must be  used and session is already  started
    */
    public function redirectCurrentUserBasedOnRole(){
        if (isset($_SESSION["role"])){
            $url = "/e-service/internal/members/";

            switch($_SESSION["role"]){
                case "professor" :
                    $url .= "professor";
                break;
                
                case "professor/chef_deparetement":
                    $url .= "professor/chef_deparetement";
                break;

                case "professor/coordonnateur":
                    $url .= "professor/coordonnateur";
                break; 

                case "admin":
                    $url .= "admin";
                break; 
                
                case "vacataire":
                    $url .= "vacataire";
                break; 
            }

            Navigation::redirect($url);

        }else {
            Navigation::redirect("/e-service/public/login.php");
        }
    }

    /**
     * This method checks if the current user has the required authority to access certain resources.
     * Implement the logic to validate user permissions based on their role or other criteria.
     * empty  array  means all roles can  see this page
     */
    public function checkCurrentUserAuthority(array $allowedRoles){

        if (!isset($_SESSION["role"])  || !(empty($allowedRoles) || in_array($_SESSION["role"],$allowedRoles))) {
            $this->redirectCurrentUserBasedOnRole();
        }
    }


}

?>