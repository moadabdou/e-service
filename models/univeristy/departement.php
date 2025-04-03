<?php 
require_once __DIR__."/../model.php"; 

class DepartementModel extends Model{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        if ($this->db->query("SELECT * FROM deparetement")){
            return $this->db->fetchAll(PDO::FETCH_ASSOC);
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }
    }

    public function getByID(int $id){

        if ($this->db->query("SELECT * FROM deparetement WHERE id_deparetement=?", [$id])){
            return $this->db->rowCount();
        }else {
            throw $this->db->getError();
        }

    }


}

?>