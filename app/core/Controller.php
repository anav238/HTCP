<?php

class Controller
{
    public function model($model, $data=[]) {
        require_once __DIR__ .'/../models/'.$model.'.php';
        return new $model($data);
    }

    public function view($view, $data=[]) {
        require_once __DIR__ .'/../views/' . $view .'.php';
    }
}

?>