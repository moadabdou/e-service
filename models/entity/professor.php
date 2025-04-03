<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/libs/passwordGenrator/passwordGenerator.php"; 

class ProfessorModel  extends UserModel{

    private PasswordGenerator $passGen; 

    public function __construct()
    {
        parent::__construct();
        $this->passGen = new PasswordGenerator();
    }

    public function newProfessor ( 
        string $firstName, 
        string $lastName,
        string $cin,
        string $email,
        int $phone,
        string $address,
        string $birth_date,
        int $departement_id,
        int $max_hours,
        int $min_hours
        ): bool {
        
        $password = $this->passGen->generate();
        $password = password_hash($password, PASSWORD_DEFAULT);

        $user_id  =  parent::newUser($firstName, $lastName, $cin, $email, "professor", $password, $phone, $address, $birth_date);
        if ($user_id ===  false){
            return false; 
        }

        if ($this->db->query("INSERT INTO professor(id_professor, max_hours, min_hours, role, id_deparetement) VALUES (?, ?, ?, ?,?)", 
            [$user_id, $max_hours, $min_hours, "normal", $departement_id])) {
            echo "here";
            return true;

        }else { 

            //user created but an error occured in  the second phase 
            //remove the created user 

            $this->error = $this->db->getError(); //store the error
            parent::deleteUserById($user_id); //delete the user, the  order is  important to not lose the previous error
            return false;
        }

    }

    public function resolveProfessorOperationError(): ?string{
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