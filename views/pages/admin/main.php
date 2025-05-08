<?php 

class MainView{

    public function view(array $data){

        ob_start();
        require __DIR__."/main.view.php";
        $content = ob_get_clean();
        return $content;

    }


}



?>