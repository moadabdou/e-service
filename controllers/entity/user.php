<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/navigation/navigation.php";

class UserController{

    /** 
     * data that  user can edit by him self
     * other data can be edited but only by the user/admin
    */
    private static array $selfEditable = ["img", "phone", "address", "password", "email"]; 
    private static string $defaultPicture = "default.webp";
    /**
     * this function must be  used and session is already  started
    */

    public function getDefaultPicture(){
        return self::$defaultPicture;
    }

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
     * empty  array  means all roles can  see this page.
     * this function must be  used and session is already  started
     */
    public function checkCurrentUserAuthority(array $allowedRoles){

        if (!isset($_SESSION["role"])  || !(empty($allowedRoles) || in_array($_SESSION["role"],$allowedRoles))) {
            $this->redirectCurrentUserBasedOnRole();
        }
    }

    
    /**
     * This method classifies entities based on their editability.
     * Implement the logic to determine which entities can be edited by the user and return the classification.
     */
    public function classifyDataBySelfEditability(array $user_data): array{
  
        foreach ( $user_data as  $key => $value ){
            if ( in_array($key, self::$selfEditable) ){

                $user_data[$key] = [
                    "value" => $value,
                    "self_editable" => true
                ];
                
            }else {

                $user_data[$key] = [
                    "value" => $value,
                    "self_editable" => false
                ];

            }

        }

        return $user_data;
    }


    public function isSelfEditable(string $dataKey){
        return  in_array($dataKey, self::$selfEditable);
    }

    public function absoluteProfilePictureUrl(string $user_img){
        return "/e-service/internal/members/common/getResource.php?type=image&path=users_pp/".htmlspecialchars($user_img);
    }



    public function isValidUserData(string $key, string|array $value) : bool{
        switch ($key){
            case 'firstName':
                return preg_match("/^[a-zA-Z '\-]*$/", $value);
            case 'lastName':
                return preg_match("/^[a-zA-Z '\-]*$/", $value);
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            case 'phone':
                return preg_match("/0[76][0-9]{8}$/", $value);
            case 'address':
                return preg_match("/^.{10,}$/", $value);
            case 'CIN':
                return preg_match("/^[A-Z]{1,2}[0-9]{5,6}$/", $value);
            case 'birth_date':
                return strtotime($value) && (time() - strtotime($value)) >= (24 * 365 * 24 * 60 * 60);
            case 'id_deparetement':
                return is_numeric($value) && $value > 0;
            case 'max_hours':
                return is_numeric($value) && $value > 0;
            case 'min_hours':
                return is_numeric($value) && $value > 0;
            case 'img':

                $integrity =is_array($value) && 
                            isset($value['error']) &&  
                            isset($value['tmp_name']) &&
                            $value['error'] === UPLOAD_ERR_OK && 
                            is_uploaded_file($value['tmp_name']) &&
                            mime_content_type($value['tmp_name']) === 'image/png';

                if (!$integrity) return false;

                $dims = getimagesize($value['tmp_name']);
                return $dims[0] === 512 && $dims[1] === 512 ;

            default:
                return false;
        }
    }

}

?>