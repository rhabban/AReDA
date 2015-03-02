<?php

require_once("categoriesLib/src/category.php");

class CategoriesDB {

  /** An instance of PDO for use by the instance. */
  protected $pdo;

  // Tables and queries ===================================================================

  /** A table name for storing Categories name, Categories id. */
  protected $table;

  /** A prepared statement for inserting name_category, idCategorys. */
  private $createCategoriesStatement;

  /** A prepared statement for retrieving all categories. */
  private $retrieveAllStatement;

  /** A prepared statement for retrieving categories by category id. */
  private $retrieveByCategoryIdStatement;

  /** A prepared statement for retrieving categories by Artwork Title. */
  private $updateByCategoryIdStatement;

  /**
   * Builds a new instance.
   * @param $pdo An instance of PDO for use by the instance. The instance must have
   * its ERRMODE set to EXCEPTION. 
   * @param $table A table name for storing persons
   */
  public function __construct (PDO $pdo, $table) {
    $this->pdo=$pdo;
    $this->table=$table;
    $values=":name_category, :email_user, ''";
    $query="INSERT INTO `".$this->table."` VALUES(".$values.")";
    $this->createCategoriesStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."`";
    $this->retrieveAllStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE id_category=:id_category";
    $this->retrieveByCategoryIdStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE email_user=:email_user";
    $this->retrieveByEmailStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET name_category=:name_category WHERE id_category=:id_category";
    $this->updateNameStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET email_user=:email_user WHERE id_category=:id_category";
    $this->updateEmailUserStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET name_category=:name_category, email_user=:email_user, id_category=:id_category WHERE id_category=:id_category";
    $this->updateCategoryStatement=$this->pdo->prepare($query);
  }

  // Create methods =======================================================================

  /**
   * Creates the necessary tables in the database. Nothing occurs for each table if it
   * already exists.
   * @throws PDOException if a database error occurs
   */
    public function createTables () {
      $query="CREATE TABLE IF NOT EXISTS `".$this->table."` (";
      $query.="`name_category` VARCHAR(255) NOT NULL, "; 
      $query.="`email_user` VARCHAR(255) NOT NULL, ";
      $query.="`id_category` int NOT NULL AUTO_INCREMENT, ";
      $query.="PRIMARY KEY(`id_category`) ";
      $query.=")";
      $this->pdo->exec($query);
    }

  public function create (Category $category) {
    $this->createCategoriesStatement->bindValue(":name_category",$category->getName());
    $this->createCategoriesStatement->bindValue(":email_user",$category->getUserEmail());
    $this->createCategoriesStatement->execute();
  }

  // Retrieve methods =====================================================================

  public function retrieveAll () {
    $this->retrieveAllStatement->execute();
    $res=array();
    while ($row=$this->retrieveAllStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new Category($row["name_category"], $row["email_user"], intval($row["id_category"]));
    return $res;
  }

  public function retrieveByCategoryId ($id_category) {
    $this->retrieveByCategoryIdStatement->bindValue(":id_category",$id_category);
    $this->retrieveByCategoryIdStatement->execute();
    $res=array();
    while ($row=$this->retrieveByCategoryIdStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new Category($row["name_category"], $row["email_user"], intval($row["id_category"]));
    return $res;
  }

  public function retrieveByEmail ($email) {
    $this->retrieveByEmailStatement->bindValue(":email_user",$email);
    $this->retrieveByEmailStatement->execute();
    $res=array();
    while ($row=$this->retrieveByEmailStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new Category($row["name_category"], $row["email_user"], intval($row["id_category"]));
    return $res;
  }

  public function retrieveLastCategoryId(){
    $query="SELECT MAX(`id_category`) FROM `".$this->table."`";
    $res = $this->pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    return $res["MAX(`id_category`)"];
  }

  // Update methods =======================================================================

  public function updateName (Category $category) {
    $this->updateNameStatement->bindValue(":name_category",$category->getName());
    $this->updateNameStatement->bindValue(":id_category",$category->getId());
    $this->updateNameStatement->execute();
  }

  public function updateEmailUser (Category $category) {
    $this->updateEmailUserStatment->bindValue(":email_user",$category->getUserEmail());
    $this->updateEmailUserStatment->bindValue(":id_category",$category->getId());
    $this->updateEmailUserStatment->execute();
  }

  public function updateCategory (Category $category) {
    $this->updateCategoryStatement->bindValue(":name_category",$category->getName());
    $this->updateCategoryStatement->bindValue(":email_user",$category->getUserEmail());
    $this->updateCategoryStatement->bindValue(":id_category",$category->getId());
    $this->updateCategoryStatement->execute();
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

}

?>

