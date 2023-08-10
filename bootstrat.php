<?php

require_once ('../vendor/autoload.php');

use Dotenv\Dotenv;
use App\Models\DatabaseConnector;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
//$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$dbConnection = (new DatabaseConnector())->getConnection();

?>