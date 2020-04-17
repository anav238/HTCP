<?php
    class Profile extends Controller 
    {
        public function index() 
        {
            $user = $this->model('User', $_SESSION['user']);
            $this->view('profile', $user);
        }
    }
?>