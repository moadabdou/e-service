<?php 
require_once __DIR__."/user.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/speciality.php";

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

    public function getProfessorRole(int $id): mixed {
        if ($this->db->query("SELECT role FROM professor WHERE id_professor = ?", [$id])) {
            return $this->db->fetchColumn(0);
        } else {
            throw new Exception("Database Error: " . $this->db->getError());
        }
    }
    

    
    // Get professor's department by user ID
    public function getProfessorByUserId($userId) {
        if ($this->db->query(sql: "SELECT id_deparetement FROM professor WHERE id_professor = ?", params: [$userId])) {
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
                    u.img as image_url,
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

        public function getProfessorsWithWorkload(int $departmentId): array {
            $query = " SELECT 
                    p.id_professor,
                    u.firstName,
                    u.lastName,
                    u.email,
                    p.min_hours,
                    p.max_hours,
                    COALESCE(SUM(m.volume_cours + m.volume_td + m.volume_tp + m.volume_autre), 0) AS assigned_hours
                FROM professor p
                JOIN user u ON u.id_user = p.id_professor
                JOIN filiere f ON f.id_deparetement = ?
                LEFT JOIN affectation_professor ap ON ap.to_professor = p.id_professor
                LEFT JOIN module m ON m.id_module = ap.id_module AND m.id_filiere = f.id_filiere
                GROUP BY p.id_professor
                ORDER BY assigned_hours DESC
            ";
        
            if ($this->db->query($query, [$departmentId])) {
                return $this->db->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [];
            }
        }

        public function getProfessorChoicesWithWorkload(int $departmentId): array {
            $query = "SELECT 
                        u.id_user,
                        u.firstName,
                        u.lastName,
                        u.email,
                        u.img,
                        p.min_hours,
                        p.max_hours,
                        SUM(m.volume_cours + m.volume_td + m.volume_tp + m.volume_autre) AS assigned_hours,
                        GROUP_CONCAT(CONCAT(m.title, '::', f.title) SEPARATOR '||') AS modules_data
                      FROM affectation_professor ap
                      JOIN user u ON ap.to_professor = u.id_user
                      JOIN professor p ON p.id_professor = u.id_user
                      JOIN module m ON ap.id_module = m.id_module
                      JOIN filiere f ON m.id_filiere = f.id_filiere
                      WHERE f.id_deparetement = ?
                      GROUP BY u.id_user
                      ORDER BY assigned_hours ASC";
        
            if ($this->db->query($query, [$departmentId])) {
                $results = $this->db->fetchAll(PDO::FETCH_ASSOC);
        
                // Parse modules_data into an array of modules with filière
                foreach ($results as &$prof) {
                    $prof['modules'] = [];
        
                    if (!empty($prof['modules_data'])) {
                        $modulesRaw = explode('||', $prof['modules_data']);
                        foreach ($modulesRaw as $item) {
                            [$title, $filiere] = explode('::', $item);
                            $prof['modules'][] = [
                                'title' => $title,
                                'filiere' => $filiere
                            ];
                        }
                    }
        
                    unset($prof['modules_data']); // Remove raw string
                }
        
                return $results;
            } else {
                return [];
            }
        }

        public function getProfessorCountByDepartment(int $departmentId): int {
            $query = "SELECT COUNT(*) FROM professor WHERE id_deparetement = ?";
            
            if ($this->db->query($query, [$departmentId])) {
                return (int) $this->db->fetchColumn();
            } else {
                return 0;
            }
        }
        
        public function getProfessorInfo(int $professorId): array {
            $query = "SELECT u.firstName, u.lastName, d.title AS department_name
                      FROM user u
                      JOIN professor p ON p.id_professor = u.id_user
                      JOIN deparetement d ON d.id_deparetement = p.id_deparetement
                      WHERE u.id_user = ?";
        
            return $this->db->query($query, [$professorId]) 
                ? $this->db->fetch(PDO::FETCH_ASSOC) 
                : [];
        }
        
        
}

?>