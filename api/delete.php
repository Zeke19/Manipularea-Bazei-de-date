<?php
include '../Database/DatabaseConn.php';
include '../Model/User.php';


header('Access-Control-Allow-Origin: *');
header('Content-Type: multipart/form-data');
header("Access-Control-Allow-Methods: DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Authentication, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$database = new Database();
$db = $database->getConnection();

$user =  new User($db);

$data = file_get_contents('php://input', false);

$user->delete((int)$data);

echo 'success';