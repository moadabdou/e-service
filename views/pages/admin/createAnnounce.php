<?php 

class CreateAnnounceView{

    public function view(array $info){


        ob_start();
        require __DIR__."/createAnnounce.view.php";
        $content = ob_get_clean();
        return $content;

    }


}



?>