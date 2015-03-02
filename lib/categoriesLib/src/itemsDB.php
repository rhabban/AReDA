<?php

require_once("categoriesLib/src/item.php");

class ItemsDB {

  /** An instance of PDO for use by the instance. */
  protected $pdo;

  // Tables and queries ===================================================================

  /** A table name for storing Item name, item value and category id. */
  protected $table;

  /** A prepared statement for inserting Item name, item value and category id. */
  private $createItemsStatement;

  /** A prepared statement for retrieving all items. */
  private $retrieveAllStatement;
  /** A prepared statement for retrieving item by item id. */
  private $retrieveByIdStatement;
  /** A prepared statement for retrieving items by category id. */
  private $retrieveByCategoryIdStatement;

  /** A prepared statement for updating item name by item id. */
  private $updateNameStatement;
  /** A prepared statement for updating item value by item id. */
  private $updateValueStatement;
  /** A prepared statement for updating category id by item id. */
  private $updateCategoryIdStatement;
  /** A prepared statement for updating item by item id. */
  private $updateItemStatement;




  /**
   * Builds a new instance.
   * @param $pdo An instance of PDO for use by the instance. The instance must have
   * its ERRMODE set to EXCEPTION. 
   * @param $table A table name for storing persons
   */
  public function __construct (PDO $pdo, $table) {
    $this->pdo=$pdo;
    $this->table=$table;
    $values=":name_item,:value_item,:id_category, ''";
    $query="INSERT INTO `".$this->table."` VALUES(".$values.")";
    $this->createItemsStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."`";
    $this->retrieveAllStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE id_item=:id_item";
    $this->retrieveByIdStatement=$this->pdo->prepare($query);
    $query="SELECT * FROM `".$this->table."` WHERE id_category=:id_category";
    $this->retrieveByCategoryIdStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET name_item=:name_item WHERE id_item=:id_item";
    $this->updateNameStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET value_item=:value_item WHERE id_item=:id_item";
    $this->updateValueStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET id_category=:id_category WHERE id_item=:id_item";
    $this->updateCategoryIdStatement=$this->pdo->prepare($query);
    $query="UPDATE `".$this->table."` SET name_item=:name_item, value_item=:value_item, id_category=:id_category WHERE id_item=:id_item";
    $this->updateItemStatement=$this->pdo->prepare($query);
  }

  // Create methods =======================================================================

  /**
   * Creates the necessary tables in the database. Nothing occurs for each table if it
   * already exists.
   * @throws PDOException if a database error occurs
   */
    public function createTables () {
      $query="CREATE TABLE IF NOT EXISTS `".$this->table."` (";
      $query.="`name_item` VARCHAR(255) NOT NULL, ";
      $query.="`value_item` VARCHAR(255) NOT NULL, ";
      $query.="`id_category` int NOT NULL,";
      $query.="`id_item` int NOT NULL AUTO_INCREMENT,";
      $query.="PRIMARY KEY(`id_item`) ";
      $query.=")";
      $this->pdo->exec($query);
    }

  public function create (Item $item) {
    $this->createItemsStatement->bindValue(":name_item",$item->getName());
    $this->createItemsStatement->bindValue(":value_item",$item->getValue());
    $this->createItemsStatement->bindValue(":id_category",$item->getCategoryId());
    $this->createItemsStatement->execute();

  }

  // Retrieve methods =====================================================================

  public function retrieveAll () {
    $this->retrieveAllStatement->execute();
    $res=array();
    while ($row=$this->retrieveAllStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new Item($row["name_item"], $row["value_item"], intval($row["id_category"]), intval($row["id_item"]));
    return $res;
  }

  public function retrieve($id) {
    $this->retrieveByIdStatement->bindValue(":id_item",$id);
    $this->retrieveByIdStatement->execute();
    if ($row=$this->retrieveByIdStatement->fetch(PDO::FETCH_ASSOC))
      return new Item($row["name_item"],$row["value_item"],intval($row["id_category"]),intval($row["id_item"]));
    throw new Exception ("No user with email $email found");
  }

  public function retrieveByCategoryId ($id_category) {
    $this->retrieveByCategoryIdStatement->bindValue(":id_category",$id_category);
    $this->retrieveByCategoryIdStatement->execute();
    $res=array();
      while ($row=$this->retrieveByCategoryIdStatement->fetch(PDO::FETCH_ASSOC))
        $res[]=new Item($row["name_item"],$row["value_item"],intval($row["id_category"]),intval($row["id_item"]));
      return $res;
  }

  public function retrieveLastItemId(){
    $query="SELECT MAX(`id_item`) FROM `".$this->table."`";
    return $this->pdo->query($query)->fetch(PDO::FETCH_ASSOC);
  }

  // Update methods =======================================================================
    
  public function updateName (Item $item) {
    $this->updateNameStatement->bindValue(":name_item",$item->getName());
    $this->updateNameStatement->bindValue(":id_item",$item->getId());
    $this->updateNameStatement->execute();
  }

  public function updateValue (Item $item) {
    $this->updateValueStatement->bindValue(":value_item",$item->getValue());
    $this->updateValueStatement->bindValue(":id_item",$item->getId());
    $this->updateValueStatement->execute();
  }

  public function updateCategoryId (Item $item) {
    $this->updateCategoryId->bindValue(":id_category",$item->getCategoryId());
    $this->updateCategoryId->bindValue(":id_item",$item->getId());
    $this->updateCategoryId->execute();
  }

  public function updateItem (Item $item) {
    $this->updateItemStatement->bindValue(":name_item",$item->getName());
    $this->updateItemStatement->bindValue(":value_item",$item->getValue());
    $this->updateItemStatement->bindValue(":id_category",$item->getCategoryId());
    $this->updateItemStatement->bindValue(":id_item",$item->getId());
    $this->updateItemStatement->execute();
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