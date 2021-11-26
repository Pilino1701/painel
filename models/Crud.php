<?php
namespace app\models;

class Crud extends Connection{ //nota, se la classe é nella stessa pasta non devo usare use
   public function create(){
   $nome=filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS);
   $email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);

   $conn=$this->connect();
   $sql="insert into tb_person values(default,:nome,:email)";//default é l'ID 
   $stmt=$conn->prepare($sql);
   $stmt->bindParam(':nome',$nome);
   $stmt->bindParam(':email',$email);
   $stmt->execute();
   return $stmt;
   }//create

   public function read(){
  	$conn=$this->connect();
  	$sql="select * from tb_person order by nome"; //nome qui é il campo della tabella
  	$stmt=$conn->prepare($sql);
  	$stmt->execute();
  	$result=$stmt->fetchAll();
  	return $result;
  }//read

  public function update(){
   $nome=filter_input(INPUT_POST,'nome',FILTER_SANITIZE_SPECIAL_CHARS);
   $email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);	
   $id=filter_input(INPUT_POST,'id',FILTER_SANITIZE_SPECIAL_CHARS);

   $conn=$this->connect();
   $sql="update tb_person set nome=:nome,email=:email where id=:id";
   $stmt=$conn->prepare($sql);
   $stmt->bindParam(':nome',$nome);
   $stmt->bindParam(':email',$email);
   $stmt->bindParam(':id',$id);
   $stmt->execute();
   return $stmt;
  }//update

  public function delete(){
    $id=base64_decode(filter_input(INPUT_GET,'id',FILTER_SANITIZE_SPECIAL_CHARS));
    $conn=$this->connect();
    $sql="delete from tb_person where id=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    return $stmt;
  	
  }//delete

 public function editForm(){
  	$id=base64_decode(filter_input(INPUT_GET,'id',FILTER_SANITIZE_SPECIAL_CHARS));

  	$conn=$this->connect();
  	$sql="select * from tb_person where id = :id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $result=$stmt->fetchAll();
    return $result;
  }//editForm
  
  

}//class Crud
?>