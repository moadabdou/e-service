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
                    COALESCE(SUM(m.volume_horaire), 0) AS assigned_hours
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
                        SUM(m.volume_horaire) AS assigned_hours,
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
        
        

        

}

?>