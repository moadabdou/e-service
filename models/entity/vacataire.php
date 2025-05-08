<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/speciality.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/vacataire.php";

class VacataireModel  extends UserModel{

    private PasswordGenerator $passGen; 

    public function __construct()
    {
        parent::__construct();
        $this->passGen = new PasswordGenerator();
    }

    public function newVacataire ( 
        string $firstName, 
        string $lastName,
        string $cin,
        string $email,
        int $phone,
        string $address,
        string $birth_date,
        int $speciality_id,
        int $id_coordonnateur
        ): array | false {
        
        $password = $this->passGen->generate();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user_id  =  parent::newUser($firstName, $lastName, $cin, $email, "vacataire", $password_hash, $phone, $address, $birth_date);
        if ($user_id ===  false){
            return false; 
        }

        if ($this->db->query("INSERT INTO vacataire(id_vacataire, speciality, id_coordonnateur) VALUES (?,?, ?)", 
            [$user_id, $speciality_id, $id_coordonnateur])) {

            return [$user_id, $password];

        }else { 
            var_dump($this->db->getError());
            parent::deleteUserById($user_id); 
            return false;
        }

    }

    public function resolveVacataireOperationError(): ?string{
        if (!$this->getError()){
            return null;
        }

        $user_error = parent::resolveUserOperationError();

        if ($user_error){
            return $user_error;
        }else{
            return null;
        }

    }
        
        
}

?>