<?php 
require_once __DIR__."/../model.php"; 

class UserModel  extends Model{

    private array $allowedColumns = ['firstName', 'lastName', 'CIN', 'email', 'role', 'password', 'phone', 'address', 'birth_date', 'img', 'status'];

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

    public  function getfullName(int $id): string {
        if ($this->db->query("SELECT CONCAT(firstName, ' ', lastName) AS full_name FROM user WHERE id_user = ?", [$id])) {
            return $this->db->fetchColumn(0);
        } else {
            throw new Exception("Database Error: " . $this->db->getError());
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

    public function getNonCriticalDataById(int $id_user) : array | false{
        if ($this->db->query("SELECT firstName, lastName, CIN, email, role, phone, address, birth_date, creation_date, img, status FROM user WHERE id_user=?", [$id_user])) {
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

    /*
    
     * type is the  type of users you  wanna  show  
     *  0 => professors
     *  1 => departements heads
     *  2 => coordinators
     *  3 => vacataires 
     *  4 => admins 
    */    

    public function getUsersByRole(int $role, int $status): array|false{

        $prefix =  "SELECT id_user,firstName,lastName,CONCAT('/e-service/internal/members/common/getResource.php?type=image&path=users_pp/', img) as img,email,phone,birth_date,creation_date FROM user ";
        $query = match ($role) {
            -1 => $prefix."WHERE 1",
            0 => $prefix."WHERE role = 'professor'",
            1 => $prefix."JOIN professor ON id_user=id_professor AND professor.role = 'chef_deparetement'",
            2 => $prefix."JOIN professor ON id_user=id_professor AND professor.role = 'coordonnateur'",
            3 => $prefix."WHERE role = 'vacataire'",
            4 => $prefix."WHERE role = 'admin'",
        };

        $query .= match ($status) {
            0 => "",
            1 => " AND status = 'active'",
            2 => " AND status = 'disabled'",
        };

        if ($this->db->query($query)) {
            return $this->db->fetchAll();
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

    public function countAllActive(): int|false {
        if ($this->db->query("SELECT COUNT(*) as count FROM user WHERE status = 'active'")) {
            $result = $this->db->fetch();
            return (int)$result['count'];
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

    public function countAllDisabled(): int|false {
        if ($this->db->query("SELECT COUNT(*) as count FROM user WHERE status = 'disabled'")) {
            $result = $this->db->fetch();
            return (int)$result['count'];
        } else {
            $this->error = $this->db->getError();
            return false;
        }
    }

}

?>