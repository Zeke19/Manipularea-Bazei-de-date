<?php

include '../Database/DatabaseConn.php';
include '../Model/User.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");


$data = new Database();
$db = $data->getConnection();

$user =  new User($db);

$useri = $user->read();

$sendUser = json_encode($useri);

echo $sendUser;
