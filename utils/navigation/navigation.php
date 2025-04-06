<?php 

class Navigation{

    public static function redirect(string $url){
        header("Location: ".$url);
        die(); //DONT FORGET 
    }

}

?>