<?php

    class HTML extends Controller 
    {
        public function index($level='1') 
        {
            $data = ['level' => $level, 'type' => 'HTML'];
            $exercise = $this->model('Exercise', $data);
            $this->view('html', $exercise);
        }
    }
?>