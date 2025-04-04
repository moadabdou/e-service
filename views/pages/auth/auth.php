<?php 
class Auth{

    public function loginView(array $errors, ?array $info){

        require __DIR__."/login.view.php";

    }

}

?>