<?php 
//echo "prueba";

require_once ('../vendor/autoload.php');
require_once ('../bootstrat.php');

use App\Controllers\AlumnoController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

//$uri[1] = "alumnos";

if ($uri[1] !== 'alumnos') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$alumnoId = null;
if (isset($uri[2])) {
    $alumnoId = (int) $uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new AlumnoController($dbConnection, $requestMethod, $alumnoId);
$controller->processRequest();




?>



