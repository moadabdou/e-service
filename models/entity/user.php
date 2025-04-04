<?php 
require_once __DIR__."/../model.php"; 

class UserModel  extends Model{

    public function __construct()
    {
        parent::__construct();
    }

    protected function newUser ( 
        string $firstName, 
        string $lastName,
        string $cin,
        string $email,
        string $role,
        string $password,
        int $phone,
        string $address,
        string $birth_date
        ): string | false{
        

        if ($this->db->query("INSERT INTO user(
                                                firstName, lastName, CIN, email, role, password, phone, address, birth_date, creation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())", 
                                                [$firstName, $lastName, $cin, $email, $role, $password, $phone, $address, $birth_date])) {
            
            return $this->db->lastInsertId();

        }else {
            $this->error = $this->db->getError();
            return false;
        }

    }

    protected function getUserByID(int $id){

        if ($this->db->query("SELECT * FROM user WHERE id_user=?", [$id])){
            return $this->db->rowCount();
        }else {
            throw $this->db->getError();
        }

    }

    protected function deleteUserById(int $id): bool {
        if ($this->db->query("DELETE FROM user WHERE id_user=?", [$id])) {
            return true;
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

    
    public function getUserByEmail(string $email) : array | false{
        if ($this->db->query("SELECT * FROM user WHERE email=?", [$email])) {
            return $this->db->fetch();
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

    protected function resolveUserOperationError(): ?string{
        if (!$this->getError()){
            return null;
        }

        if (strpos($this->getError(), "email") !== false){
            return "email is already exists";
        }else{
            return null;
        }

    }
}

?>