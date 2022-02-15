<?php

/*
CREATE DATABASE `user` 
*/

class Database {

    private $host;

    private $username; 

    private $parola;

    private $dbaname;

    public $connection;
    
    public function __construct($host='localhost',$username= 'root',$parola='',$dbaname= 'user')
    {
        $this->connection = new mysqli($host,$username,$parola,$dbaname);

        if ($this->connection->connect_error) 
        {
            die("Connection failed: " . $connection->connect_error);
        }

    }

    public function getConnection()
    {
        return $this->connection;
    }

}

?>