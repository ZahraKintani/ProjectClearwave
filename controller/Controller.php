<?php

class Controller {

    function loadView($view = '', $data = []){
        foreach($data as $key => $val)
            $$key = $val;
        include 'view/' . $view;
    }
    
    function loadModel($model = ''){
        require_once('model/Model.class.php');
        require_once('model/' . $model . '.class.php');
        return new $model;
    }

    function redirect($url) {
        header("Location: $url");
        exit;
    }
}
?>