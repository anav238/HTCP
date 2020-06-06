<?php

session_start();

require_once 'config.php';
require_once 'core/DB.php';
$db_class = new DB();

require_once 'core/App.php';
require_once 'core/Controller.php';