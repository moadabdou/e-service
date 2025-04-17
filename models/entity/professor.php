<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 

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
        ): array | false {
        
        $password = $this->passGen->generate();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $user_id  =  parent::newUser($firstName, $lastName, $cin, $email, "professor", $password_hash, $phone, $address, $birth_date);
        if ($user_id ===  false){
            return false; 
        }

        if ($this->db->query("INSERT INTO professor(id_professor, max_hours, min_hours, role, id_deparetement) VALUES (?, ?, ?, ?,?)", 
            [$user_id, $max_hours, $min_hours, "normal", $departement_id])) {

            return [$user_id, $password];

        }else { 

            //user created but an error occured in  the second phase 
            //remove the created user 

            $this->error = $this->db->getError(); //store the error
            parent::deleteUserById($user_id); //delete the user, the  order is  important to not lose the previous error
            return false;
        }

    }


    public function getProfessorRole(int $id){

        if ($this->db->query("SELECT role FROM professor WHERE id_professor=?", [$id])){
            return $this->db->fetchColumn(0);
        }else {
            throw $this->db->getError();
        }

    }
    // Get professor's department by user ID
    public function getProfessorByUserId($userId) {
        if ($this->db->query("SELECT id_deparetement FROM professor WHERE id_professor = ?", [$userId])) {
            return $this->db->fetch(); // returns ['id_deparetement' => ...]
        } else {
            $this->error = $this->db->getError();
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