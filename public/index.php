<?php
require "../bootstrap.php";

use src\Controller\AdvertisementController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriArray = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];


// pass the request method and user ID to the PersonController and process the HTTP request:
if ($uriArray[1] == 'ads') {
    $controller = new AdvertisementController($dbConnection);
    $controller->processRequest($requestMethod, $uriArray);
}
