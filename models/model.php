<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/core/Database.php"; 

class Model{


    protected Database $db;
    protected ?string $error = null;

    public function __construct()
    {
        $this->db =  new Database();
        if($this->db->getError()){
            throw $this->db->getError();  //if an  error happened during initializing just  throw it 
        }
    }

    public function getError() : ?string {
        return $this->error;
    }


}
?>