<?php 
require_once __DIR__."/../model.php"; 

class UserModel  extends Model{

    private array $allowedColumns = ['firstName', 'lastName', 'CIN', 'email', 'role', 'password', 'phone', 'address', 'birth_date', 'img'];

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

    public function getUserByID(int $id){

        if ($this->db->query("SELECT * FROM user WHERE id_user=?", [$id])){
            return $this->db->fetch();
        }else {
            throw $this->db->getError();
        }

    }

    protected function deleteUserById(int $id): bool {
        if ($this->db->query("DELETE FROM user WHERE id_user=?", [$id])) {
            return $this->db->rowCount()>0;
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

    public function getNonCriticalDataById(string $id_user) : array | false{
        if ($this->db->query("SELECT firstName, lastName, CIN, email, role, phone, address, birth_date, creation_date, img FROM user WHERE id_user=?", [$id_user])) {
            return $this->db->fetch();
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

    public function updateUserColumn(string $columnName, string $value, string $userId): bool{ /*just  realized that this userId should be given in the constructor*/
        if (!in_array($columnName, $this->allowedColumns)) {
            $this->error = "Invalid data key";
            return false;
        }
        $query = "UPDATE user SET `" . $columnName . "` = ? WHERE id_user = ?";
        if ($this->db->query($query, [$value, $userId])) {
            return $this->db->rowCount() > 0;
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }


    protected function resolveUserOperationError(): ?string{
        if (!$this->getError()){
            return null;
        }
        if (strpos($this->getError(), "key 'email'") !== false){
            return "email is already exists ";
        }else if (strpos($this->getError(), "key 'CIN'") !== false){
            return "CIN is already exists";
        }else{
            return null;
        }

    }
}

?>