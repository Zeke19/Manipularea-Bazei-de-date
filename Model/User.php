<?php
/*
CREATE TABLE `user` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `nume` varchar(255) NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 `age` mediumint(9) DEFAULT NULL,
 `email` varchar(255) DEFAULT NULL,
 `telefon` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4
*/


class User {

  private $con;


  public function __construct($con) 
  {
        $this->con = $con;
  }

  public  function read(): array
  {
      
      $conn = $this->con;

      $sql = "
      Select 
      id, 
      nume,
      age, 
      email,
      telefon
      from user "  ;

      $stmt = $conn->prepare($sql);

      $stmt->execute();

      $resultSet = $stmt->get_result();
    
      $result= [];

      while ($row = $resultSet->fetch_assoc())
      {
          $result[]=$row;
      } 

      return $result;
  }

  public function readSingle(int $id) : ?array
  {

      if($id === 0)
        $param = '(SELECT MAX(id) from user)';
      else
        $param = $id;

      $conn = $this->con;

      $sql = "
      Select 
      id, 
      nume,
      age, 
      email,
      telefon
      from user
      where id = $param
      ";

      $stmt = $conn->prepare($sql);

      $stmt->execute();

      $result = $stmt->get_result();

      return $result->fetch_assoc();
    
  }


  public function create( string $nume, int $age, string $telefon, string $email) :void
  {
      $con = $this->con;

      $sql = "
      Insert INTO user 
      SET
      nume = ?,
      age =  ?,
      telefon = ?,
      email = ?
        "
        ;

      $stmt = $con->prepare($sql);

      $stmt->bind_param('siss', $nume , $age, $telefon, $email );

      $stmt->execute();
  
  }

  public function delete( int $id) :void
  {
      $con = $this->con;

      $sql = "
      DELETE FROM user 
      WHERE 
      id = ?";

      $stmt = $con->prepare($sql);

      $stmt ->bind_param('i', $id);

      $stmt->execute();
  }

  public function edit(array $data):void 
  {
    $con = $this->con;
    $nume=  $data["nume"];
    $sql = "
    UPDATE user
    SET
    ";
    
    $sql.= ($data["nume"] !== 0) ? "nume = '".$nume."'," : '';

    $sql.= ($data["age"] !== 0)  ?  'age = '.$data["age"].',' : '';
     
    $sql.= ($data["telefon"] !== 0) ? "telefon = '".$data["telefon"]."'," : '';

    $sql.= ($data["email"] !== 0 ) ?  "email = '".$data["email"]."'" : '';
    
    if($data["nume"] !== 0 || $data["age"] !== 0 || $data["telefon"] !== 0 || $data["email"] !== 0) 
    {  
        if($sql[strlen($sql)-1]== ',') 
          $newSql= substr($sql, 0 , -1);
        else
          $newSql = $sql;
          
        $newSql.= ' WHERE id = '.$data["id"];    

        $stmt = $con->prepare($newSql);
    
        $stmt->execute();  
    }
  }


}





?>