<?php

require_once("personsdblib/src/personsdb.php");
require_once("personRank.php");

class PersonsRankDB extends PersonsDB{
    //A REFAIRE
    private $addUserStatement;

    private $changeUserStatement;

    private $changePasswordStatment;

    private $readUsersStatement;




    public function __construct (PDO $pdo, $table) {
      parent::__construct($pdo, $table);
      $this->pdo=$pdo;
      $this->table=$table;
      $values=":email,SHA1(:password),:lastName,:firstName,:rank";
      $query="INSERT INTO `".$this->table."` VALUES(".$values.")";
      $this->addUserStatement=$this->pdo->prepare($query);
      $query="SELECT * FROM `".$this->table."`";
      $this->readUsersStatement=$this->pdo->prepare($query);
      //$query="SELECT `email` FROM `".$this->table."`";
      //$this->readEmailsStatement=$this->pdo->prepare($query);
      $query="SELECT * FROM `".$this->table."` WHERE email=:email";
      $this->readUserStatement=$this->pdo->prepare($query);
      $query="SELECT * FROM `".$this->table."` WHERE rank=:rank";
      $this->readUsersByRankStatement=$this->pdo->prepare($query);
      $query="UPDATE `".$this->table."` SET lastName=:lastName,firstName=:firstName, rank=:rank, email=:email WHERE email=:oldEmail";
      $this->changeUserStatement=$this->pdo->prepare($query);
    }
    
    public function createTables () {
      $query="CREATE TABLE IF NOT EXISTS `".$this->table."` (";
      $query.="`email` VARCHAR(255) NOT NULL, ";
      $query.="`password` VARCHAR(255) NOT NULL, ";
      $query.="`lastName` VARCHAR(100), ";
      $query.="`firstName` VARCHAR(100), ";
      $query.="`rank` VARCHAR(100) NOT NULL, ";
      $query.="PRIMARY KEY (`email`) ";
      $query.=")";
      $this->pdo->exec($query);
    }
    
    public function add (PersonRank $user, $password) {
      $this->addUserStatement->bindValue(":lastName",$user->getLastName());
      $this->addUserStatement->bindValue(":firstName",$user->getFirstName());
      $this->addUserStatement->bindValue(":email",$user->getEmail());
      $this->addUserStatement->bindValue(":password",$password);
      $this->addUserStatement->bindValue(":rank",$user->getRank());
      $this->addUserStatement->execute();
    }

    public function retrieveAll () {
      $this->readUsersStatement->execute();
      $res=array();
      while ($row=$this->readUsersStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new PersonRank($row["firstName"],$row["lastName"],$row["email"],$row["rank"]);
      return $res;
    }
    
    public function retrieve ($email) {
      $this->readUserStatement->bindValue(":email",$email);
      $this->readUserStatement->execute();
      if ($row=$this->readUserStatement->fetch(PDO::FETCH_ASSOC))
        return new PersonRank($row["firstName"],$row["lastName"],$row["email"],$row["rank"]);
      throw new Exception ("No user with email $email found");
    }

    public function retrieveByRank ($rank) {
      $this->readUsersByRankStatement->bindValue(":rank",$rank);
      $this->readUsersByRankStatement->execute();
      if ($row=$this->readUsersByRankStatement->fetch(PDO::FETCH_ASSOC))
        return new PersonRank($row["firstName"],$row["lastName"],$row["email"],$row["rank"]);
      throw new Exception ("No user with rank $rank found");
    }
    
    public function change ($email, PersonRank $user) {
      $this->changeUserStatement->bindValue(":oldEmail",$email);
      $this->changeUserStatement->bindValue(":lastName",$user->getLastName());
      $this->changeUserStatement->bindValue(":firstName",$user->getFirstName());
      $this->changeUserStatement->bindValue(":email",$user->getEmail());
      $this->changeUserStatement->bindValue(":rank",$user->getRank());
      $this->changeUserStatement->execute();
    }

    public function deleteTables () {
      $this->pdo->exec("DROP TABLE IF EXISTS `".$this->table."`");
    }


}
