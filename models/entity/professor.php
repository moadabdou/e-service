<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 

class ProfessorModel  extends UserModel{

    private static array  $roles = ['normal','chef_deparetement','coordonnateur'];

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

 //store the error
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

    public function setAsDepartementHead(int $id_prof) : bool{

        $query = "
            UPDATE professor SET 
            role = CASE 
            WHEN id_professor = ? THEN 'chef_deparetement'
            WHEN role = 'chef_deparetement' THEN 'normal'
            END
            WHERE id_professor = ? OR role = 'chef_deparetement'
        ";
        if ($this->db->query($query, [$id_prof,$id_prof])) {
            return $this->db->rowCount() > 0;
        } else {

            return false;
        }

    }

     // Get all professors in a department except the prof who is in the page
     public function getProfessorsByDepartmentex(int $departmentId, int $profId): array {
        $query = "SELECT 
                    u.id_user,
                    u.firstName,
                    u.lastName,
                    u.email,
                    u.phone,
                    u.CIN,
                    u.role as u_role,
                    p.role as p_role,
                    p.min_hours,
                    p.max_hours
                  FROM user u
                  JOIN professor p ON u.id_user = p.id_professor
                  WHERE p.id_deparetement = ? AND p.id_professor <> ?";

        if ($this->db->query($query, [$departmentId,$profId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Get all professors in a department
    public function getProfessorsByDepartment(int $departmentId): array {
        $query = "SELECT 
                    u.id_user,
                    u.firstName,
                    u.lastName,
                    u.email,
                    u.phone,
                    u.CIN,
                    u.role as u_role,
                    p.role as p_role,
                    p.min_hours,
                    p.max_hours
                    FROM user u
                    JOIN professor p ON u.id_user = p.id_professor
                    WHERE p.id_deparetement = ?";

        if ($this->db->query($query, [$departmentId])) {
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function assignModuleToProfessor(int $moduleId, int $professorId): bool {
        $query = "INSERT INTO choix_module (id_module, by_professor, status, date_creation)
                    VALUES (?, ?, 'validated', NOW())";

        return $this->db->query($query, [$moduleId, $professorId]);
    }

    public function updateProfessorDepartment(int $professorId, int $departmentId): bool {
        $query = "UPDATE professor 
                 SET id_deparetement = ? 
                 WHERE id_professor = ?";

        return $this->db->query($query, [$departmentId, $professorId]);
    }

    public function setAsFiliereCoordinator(int $id_prof,  int $id_filiere): bool {
        $this->db->beginTransaction();

        //get and remove the old coordinator
        if (!$this->db->query("SELECT id_coordonnateur FROM coordonnateur WHERE id_filiere = ?", [$id_filiere])) {
            $this->db->rollBack();
            return false;
        }
        $old_coordinator = $this->db->fetch();

        if (!$this->db->query("DELETE FROM coordonnateur WHERE id_filiere = ?", [$id_filiere])) {
            $this->db->rollBack();
            return false;
        }

        if ($old_coordinator) {
            // Reset the old coordinator's role to normal
            if (!$this->db->query("UPDATE professor SET role='normal' WHERE id_professor=?", [$old_coordinator['id_coordonnateur']])) {
                $this->db->rollBack();
                return false;
            }
        }

        //insert the new coordinator 

        if (!$this->db->query("INSERT INTO coordonnateur(
            id_coordonnateur, id_filiere) VALUES (? ,?)", [$id_prof, $id_filiere])){
            
            $this->db->rollBack();
            return false;
        }

        //change role of the new coordinator
        if (!$this->db->query("UPDATE professor  SET role='coordonnateur'  WHERE id_professor=?", [$id_prof])) {
            $this->db->rollBack();
            return false;
        }

        $this->db->commit();
        return true;
    }

}

?>