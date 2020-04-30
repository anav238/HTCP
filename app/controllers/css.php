<?php
    class CSS extends Controller 
    {
        public function index($level='1') 
        {
            $data = ['level' => $level, 'type' => 'CSS'];
            $exercise = $this->model('Exercise', $data);
            $this->view('css', $exercise);
        }
    }