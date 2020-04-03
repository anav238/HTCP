<?php

require_once 'config.php';

require_once 'models/DBClass.php';
$db_class = new DBClass();
$db_class->getConnection();

require_once 'core/App.php';
require_once 'core/Controller.php';

?>