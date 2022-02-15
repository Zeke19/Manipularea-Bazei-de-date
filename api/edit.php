<?php

include '../Database/DatabaseConn.php';
include '../Model/User.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: multipart/form-data');
header("Access-Control-Allow-Methods: POST");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Authentication, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");


$database = new Database();
$db = $database->getConnection();

$user =  new User($db);

$data = file_get_contents('php://input', false);

$newData = array();

parse_str($data, $newData);

$oldDataOfUser = $user->readSingle($newData["id"]);

$filteredArrayOfdata["id"] = (int)$newData["id"];

$filteredArrayOfdata["nume"] = $newData["nume"] == $oldDataOfUser["nume"] ? 0 : $newData["nume"];

$filteredArrayOfdata["age"] = $newData["age"] == $oldDataOfUser["age"] ? 0 : $newData["age"];

$filteredArrayOfdata["telefon"] = $newData["telefon"] == $oldDataOfUser["telefon"] ? 0 : $newData["telefon"];

$filteredArrayOfdata["email"] = $newData["email"] == $oldDataOfUser["email"] ? 0 : $newData["email"];

$user->edit($filteredArrayOfdata);

echo 'success';
