<?php 

class DepartementController{

    public function isValidData(string $key, string|array $value) : bool{
        switch ($key){
            case 'title':
                return preg_match("/^[a-zA-Z ]{8,50}$/", $value);
            case 'description':
                return preg_match("/^[a-zA-Z0-9\s.,!?()-]{200,400}$/", $value);
            default:
                return false;
        }
    }

};


?>