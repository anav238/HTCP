<?php

class App 
{
    protected $controller = 'html';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {

        $url = $this->parseUrl();
            
        if (isset($url[0])) { 
            if (file_exists(__DIR__.'/../controllers/'.$url[0].'.php')) {
                $this->controller = $url[0];
                unset($url[0]);
            }
            else 
                $this->controller = "notfound";
        }
        
        //If the user is not logged in and tries to access other pages on the website, he is redirected to the login page.
        if ($this->controller != 'api' && $this->controller != 'githubconnect' && $this->controller != "notfound"
                && !isset($_SESSION['accessToken']))
            $this->controller = 'login';
    
        else if ($this->controller == 'login' && isset($_SESSION['accessToken']))
            $this->controller = 'html';

        require_once __DIR__.'/../controllers/'.$this->controller.'.php';
        $controller = new $this->controller;
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        $this->params = $url?array_values($url):[];
        call_user_func_array([$controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        if (isset($_GET['url'])) 
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
}
