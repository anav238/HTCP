<?php

session_start();

if(stream_resolve_include_path('config.php'))
  require_once 'config.php';

require_once 'core/DB.php';
$db_class = new DB();

require_once 'core/App.php';
require_once 'core/Controller.php';
