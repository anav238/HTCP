<?php
    class Home extends Controller 
    {
        public function index($name='') 
        {
            $user = $this->model('User');
            $user->name = 'Alex';
            $this->view('home/index', ['name' => $user->name]);
        }
    }
?>