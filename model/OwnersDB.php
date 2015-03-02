<?php

class OwnersDB {

  /** An instance of PDO for use by the instance. */
  protected $pdo;

  // Tables and queries ===================================================================

  /** A table name for storing persons email and Artworks id. */
  protected $table;

  /** A prepared statement for inserting a couple userEmail, idArtwork. */
  private $createOwnersStatement;

  /** A prepared statement for retrieving all couple. */
  private $retrieveAllStatement;

  /** A prepared statement for retrieving a couple by User Email. */
  private $retrieveByEmailStatement;

  /** A prepared statement for retrieving a couple by Artwork Title. */
  private $updateByTitleStatement;

  /**
   * Builds a new instance.
   * @param $pdo An instance of PDO for use by the instance. The instance must have
   * its ERRMODE set to EXCEPTION. 
   * @param $table A table name for storing persons
   */
  public function __construct (PDO $pdo, $table) {
    $this->pdo=$pdo;
    $this->table=$table;
    $values=":userEmail,:artworkTitle";
    $query="INSERT INTO `".$this->table."` VALUES(".$values.")";
    $this->createOwnersStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."`";
    $this->retrieveAllStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE userEmail=:userEmail";
    $this->retrieveByEmailStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE artworkTitle=:artworkTitle";
    $this->retrieveByTitleStatement=$this->pdo->prepare($query);
  }

  // Create methods =======================================================================

  /**
   * Creates the necessary tables in the database. Nothing occurs for each table if it
   * already exists.
   * @throws PDOException if a database error occurs
   */
    public function createTables () {
      $query="CREATE TABLE IF NOT EXISTS `".$this->table."` (";
      $query.="`userEmail` VARCHAR(255) NOT NULL, ";
      $query.="`artworkTitle` VARCHAR(255) NOT NULL ";
      $query.=")";
      $this->pdo->exec($query);
    }

  public function create (Person $person, Work $artWork) {
    $this->createOwnersStatement->bindValue(":userEmail",$person->getEmail());
    $this->createOwnersStatement->bindValue(":artworkTitle",$artWork->getTitle());
    $this->createOwnersStatement->execute();
  }

  // Retrieve methods =====================================================================

  public function retrieveAll () {
    $this->retrieveAllStatement->execute();
    $res=array();
    while ($row=$this->retrieveAllStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=array($row["userEmail"], $row["artworkTitle"]);
    return $res;
  }

  public function retrieveByTitle ($title) {
    $this->retrieveByTitleStatement->bindValue(":artworkTitle",$title);
    $this->retrieveByTitleStatement->execute();
    $res=array();
    while ($row=$this->retrieveByTitleStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=array($row["userEmail"], $row["artworkTitle"]);
    return $res;
  }

  public function retrieveByEmail ($email) {
    $this->retrieveByTitleStatement->bindValue(":userEmail",$email);
    $this->retrieveByEmailStatement->execute();
    $res=array();
    while ($row=$this->retrieveByEmailStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=array($row["userEmail"], $row["artworkTitle"]);
    return $res;
  }

  // Update methods =======================================================================


  // Delete methods =======================================================================

  /**
   * Drops the tables from the database. Nothing occurs for each table if it does not
   * exist.
   * @throws PDOException if a database error occurs
   */
  public function deleteTables () {
    $this->pdo->exec("DROP TABLE IF EXISTS `".$this->table."`");
  }

}

?>
