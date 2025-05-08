<?php 

class AnnouncesPage{

    public function view(array $announces){
        ob_start();
        require_once __DIR__."/announces.view.php";
        return ob_get_clean();
    }

}

?>