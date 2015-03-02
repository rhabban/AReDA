<?php

require_once("personslib/src/person.php");
require_once("personslib/src/ipersondb.php");

/**
 * A class for storing persons with passwords. The person's email is taken as a
 * unique identifier for her. The passwords are stored in the database and candidate
 * passwords can be checked against them, but they cannot be extracted from the
 * database.
 * Email addresses longer than 500 characters are not properly handled.
 * This class implements interface IPersonDB from package persons; please consult the documentation
 * of the latter package for overriden methods.
 * @author Bruno Zanuttini, Universit&eacute; de Caen Basse-Normandie, France
 * @since March, 2012
 */
class PersonsDB implements IPersonDB {

  /** An instance of PDO for use by the instance. */
  protected $pdo;

  // Tables and queries ===================================================================

  /** A table name for storing persons. */
  protected $table;

  /** A prepared statement for inserting a person. */
  private $createPersonStatement;

  /** A prepared statement for retrieving all persons. */
  private $retrievePersonsStatement;

  /** A prepared statement for retrieving all emails. */
  private $retrieveEmailsStatement;

  /** A prepared statement for retrieving a person. */
  private $retrievePersonStatement;

  /** A prepared statement for updating a person's information. */
  private $updatePersonStatement;

  /** A prepared statement for updating a person's password. */
  private $updatePasswordStatement;

  /** A prepared statement for checking a password. */
  private $checkPasswordStatement;

  /** A prepared statement for deleting a person. */
  private $deleteResultStatement;

  /**
   * Builds a new instance.
   * @param $pdo An instance of PDO for use by the instance. The instance must have
   * its ERRMODE set to EXCEPTION. 
   * @param $table A table name for storing persons
   */
  public function __construct (PDO $pdo, $table) {
    $this->pdo=$pdo;
    $this->table=$table;
    $values=":lastName,:firstName,:email,SHA1(:password)";
    $query="INSERT INTO `".$this->table."` VALUES(".$values.")";
    $this->createPersonStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."`";
    $this->retrievePersonsStatement=$this->pdo->prepare($query);
    $query="SELECT `email` FROM `".$this->table."`";
    $this->retrieveEmailsStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE email=:email";
    $this->retrievePersonStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET lastName=:lastName,firstName=:firstName,email=:email WHERE email=:oldEmail";
    $this->updatePersonStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET password=SHA1(:password) WHERE email=:email";
    $this->updatePasswordStatement=$this->pdo->prepare($query);
    $clause="email=:email AND password=SHA1(:password)";
    $query="SELECT * FROM `".$this->table."` WHERE ".$clause;
    $this->checkPasswordStatement=$this->pdo->prepare($query);
    $query="DELETE FROM `".$this->table."` WHERE email=:email";
    $this->deletePersonStatement=$this->pdo->prepare($query);
  }

  // Create methods =======================================================================

  /**
   * Creates the necessary tables in the database. Nothing occurs for each table if it
   * already exists.
   * @throws PDOException if a database error occurs
   */
  public function createTables () {
    $query="CREATE TABLE IF NOT EXISTS `".$this->table."` (";
    $query.="`lastName` text NOT NULL, ";
    $query.="`firstName` text NOT NULL, ";
    $query.="`email` varchar(200) NOT NULL, ";
    $query.="`password` text NOT NULL, ";
    $query.="PRIMARY KEY (`email`) ";
    $query.=")";
    $this->pdo->exec($query);
  }

  public function create (Person $person, $password) {
    $this->createPersonStatement->bindValue(":lastName",$person->getLastName());
    $this->createPersonStatement->bindValue(":firstName",$person->getFirstName());
    $this->createPersonStatement->bindValue(":email",$person->getEmail());
    $this->createPersonStatement->bindValue(":password",$password);
    $this->createPersonStatement->execute();
  }

  // Retrieve methods =====================================================================

  public function retrieveAll () {
    $this->retrievePersonsStatement->execute();
    $res=array();
    while ($row=$this->retrievePersonsStatement->fetch(PDO::FETCH_ASSOC))
      $res[]=new Person($row["firstName"],$row["lastName"],$row["email"]);
    return $res;
  }

  public function retrieveAllEmails () {
    $this->retrieveEmailsStatement->execute();
    $res=array();
    while ($row=$this->retrieveEmailsStatement->fetch(PDO::FETCH_ASSOC))
      $res[]=$row["email"];
    return $res;
  }

  public function retrieve ($email) {
    $this->retrievePersonStatement->bindValue(":email",$email);
    $this->retrievePersonStatement->execute();
    if ($row=$this->retrievePersonStatement->fetch(PDO::FETCH_ASSOC))
      return new Person($row["firstName"],$row["lastName"],$row["email"]);
    throw new Exception ("No person with email $email found");
  }

  public function isValid ($email, $password) {
    $this->checkPasswordStatement->bindValue(":email",$email);
    $this->checkPasswordStatement->bindValue(":password",$password);
    $this->checkPasswordStatement->execute();
    // Association exists if and only if the result contains at least one line
    return $this->checkPasswordStatement->fetch(PDO::FETCH_ASSOC);
  }

  public function exists ($email) {
    $this->retrievePersonStatement->bindValue(":email",$email);
    $this->retrievePersonStatement->execute();
    return $this->retrievePersonStatement->fetch()!==false;
  }

  // Update methods =======================================================================

  public function update ($email, Person $person) {
    $this->updatePersonStatement->bindValue(":oldEmail",$email);
    $this->updatePersonStatement->bindValue(":lastName",$person->getLastName());
    $this->updatePersonStatement->bindValue(":firstName",$person->getFirstName());
    $this->updatePersonStatement->bindValue(":email",$person->getEmail());
    $this->updatePersonStatement->execute();
  }

  public function updatePassword ($email, $password) {
    $this->updatePasswordStatement->bindValue(":email",$email);
    $this->updatePasswordStatement->bindValue(":password",$password);
    $this->updatePasswordStatement->execute();
  }

  // Delete methods =======================================================================

  /**
   * Drops the tables from the database. Nothing occurs for each table if it does not
   * exist.
   * @throws PDOException if a database error occurs
   */
  public function deleteTables () {
    $this->pdo->exec("DROP TABLE IF EXISTS `".$this->table."`");
  }

  public function delete ($email) {
    $this->deletePersonStatement->bindValue(":email",$email);
    $this->deletePersonStatement->execute();
  }

}

?>
