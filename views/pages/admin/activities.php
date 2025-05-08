<?php 

class ActivitiesPage{

    public function view(array $activities){

        ob_start();
        require __DIR__."/activities.view.php";
        $content = ob_get_clean();
        return $content;

    }


}



?>

?>