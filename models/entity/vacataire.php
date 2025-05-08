<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/speciality.php";

class VacataireModel  extends UserModel{

    private static array  $roles = ['normal','chef_deparetement','coordonnateur'];

    private PasswordGenerator $passGen; 

    public function __construct()
    {
        parent::__construct();
        $this->passGen = new PasswordGenerator();
    }

    public function newVaca ( 
        string $firstName, 
        string $lastName,
        string $cin,
        string $email,
        int $phone,
        string $address,
        string $birth_date,
        int $speciality_id,
        int $max_hours,
        int $min_hours
        ): array | false {
        
        $password = $this->passGen->generate();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user_id  =  parent::newUser($firstName, $lastName, $cin, $email, "professor", $password_hash, $phone, $address, $birth_date);
        if ($user_id ===  false){
            return false; 
        }

        $departement_id =  (new SpecialityModel())->getDeparetementID($speciality_id);
        

        if (!is_int($departement_id)){
            return false;
        }

        if ($this->db->query("INSERT INTO professor(id_professor, max_hours, min_hours, role, id_deparetement, id_speciality) VALUES (?, ?, ?, ?,?, ?)", 
            [$user_id, $max_hours, $min_hours, "normal", $departement_id, $speciality_id])) {

            return [$user_id, $password];

        }else { 
            var_dump($this->db->getError());
            //user created but an error occured in  the second phase 
            //remove the created user 

 //store the error
            parent::deleteUserById($user_id); //delete the user, the  order is  important to not lose the previous error
            return false;
        }

    }
        
        
}

?>