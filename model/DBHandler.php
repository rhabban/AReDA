<?php

require_once("personsRankLib/personRankDB.php");
require_once("categoriesLib/src/categoriesDB.php");
require_once("categoriesLib/src/itemsDB.php");
require_once("OwnersDB.php");

/**
 * @author Chupin Corentin, Universit&eacute; de Caen Basse-Normandie, France
 * @since February, 2015
 */
class DBHandler {

  /** A database handler for users. */
  public $usersDB;

  /** A database handler for categories. */
  protected $categoriesDB;

  /** A database handler for categories owners. */
  protected $categoriesOwnersDB;

    /** A database handler for items. */
  protected $itemsDB;

  /**
   * Builds a new instance.
   * @param $pdo An instance of PDO
   * @throws PDOException if a database error occurs
   */
  public function __construct (PDO $pdo) {
    $this->usersDB=new PersonsRankDB($pdo,"users");
    $this->categoriesDB=new CategoriesDB($pdo,"categories");
    $this->itemsDB=new ItemsDB($pdo,"Items");
  }

  // Create methods ======================================================================

  /**
   * Creates all the required tables. For each table, nothing occurs if the table
   * already exists.
   * @throws PDOException if a database error occurs
   */
  public function createTables () {
    $this->usersDB->createTables();
    $this->categoriesDB->createTables();
    $this->itemsDB->createTables();
  }

  /**
   * Saves a new user.
   * @param $user The new user
   * @return The id assigned to this championship in the database
   * @throws PDOException if a database error occurs
   */
  public function createUser (PersonRank $user, $password) {
    $this->usersDB->add($user, $password);
    $this->createDefaultsCategories($user);
  }

  /**
   * Saves a new Category.
   * @param $category The caetegory
   * @param $user The user
   * @return The id assigned to this championship in the database
   * @throws PDOException if a database error occurs
   */
  public function createCategory ($name_category, $email_user) {
    $category= new Category($name_category, $email_user);
    $newId = $this->retrieveLastCategoryId() + 1;
    $this->categoriesDB->create($category);
    $category->setId($newId);
    return $category;
  }

  /**
   * Saves defaults Categories.
   * @param $category The category
   * @param $user The user
   * @return The id assigned to this championship in the database
   * @throws PDOException if a database error occurs
   */
  public function createDefaultsCategories ($user) {
    $email = $user->getEmail();
    $category1 = $this->createCategory('Identité', $email);
    $this->createItem(new Item("Nom", $user->getLastName(), $category1->getId()));
    $this->createItem(new Item("Prénom", $user->getFirstName(), $category1->getId()));
    $this->createItem(new Item("Sexe", '', $category1->getId()));
    $this->createItem(new Item("Date de naissance", '', $category1->getId()));
    $this->createItem(new Item("Lieu de naissance", '',$category1->getId()));
    $this->createItem(new Item("Taille", '', $category1->getId()));

    $category2 = $this->createCategory('Coordonnées', $email);
    $this->createItem(new Item("Adresse", '', $category2->getId()));
    $this->createItem(new Item("Code postal", '', $category2->getId()));
    $this->createItem(new Item("Ville", '', $category2->getId()));
    $this->createItem(new Item("Email", '', $category2->getId()));
    $this->createItem(new Item("Téléphone", '', $category2->getId()));
  }


  /**
   * Saves a new Item.
   * @param $item The item
   * @param $category The category of item
   * @return The id assigned to this championship in the database
   * @throws PDOException if a database error occurs
   */
  public function createItem (Item $item) {
    $this->itemsDB->create($item);
    $item->setId($this->retrieveLastItemId());
  }

  // Retrieval methods ===================================================================

  /**
   * Returns the list of all users stored in the database.
   * @return An associative array $id => $user
   * @throws PDOException if a database error occurs
   */
  public function retrieveAllUsers () {
    return $this->usersDB;
  }

  /**
   * Returns the list of all tournaments stored in the database.
   * @return An associative array $id => $tournament
   * @throws PDOException if a database error occurs
   */
  public function retrieveUsersByRank ($rank) {
    return $this->usersDB->retrieveByRank($rank);
  }

  /**
   * Returns the list of all tournaments stored in the database.
   * @return An user
   * @throws PDOException if a database error occurs
   */
  public function retrieveUser ($email) {
    return $this->usersDB->retrieve($email);
  }

  /**
   * Returns the Item with id stored in the database.
   * @return A category
   * @throws PDOException if a database error occurs
   */
  public function retrieveCategory($id) {
    return $this->categoriesDB->retrieveByCategoryId($id);
  }

  /**
   * Returns the category with id stored in the database.
   * @return A category
   * @throws PDOException if a database error occurs
   */
  public function retrieveCategoriesByEmail($email) {
    return $this->categoriesDB->retrieveByEmail($email);
  }

  /**
   * Returns the last Category id stored in the database.
   * @return An int
   * @throws PDOException if a database error occurs
   */
  public function retrieveLastCategoryId() {
    return $this->categoriesDB->retrieveLastCategoryId();
  }

  /**
   * Returns the Item with id stored in the database.
   * @return An item
   * @throws PDOException if a database error occurs
   */
  public function retrieveItem($id) {
    return $this->itemsDB->retrieve($id);
  }

  /**
   * Returns the Item with id stored in the database.
   * @return a list of item
   * @throws PDOException if a database error occurs
   */
  public function retrieveItemsByCategory($id_category) {
    return $this->itemsDB->retrieveByCategoryId($id_category);
  }

  /**
   * Returns the last Item id stored in the database.
   * @return An int
   * @throws PDOException if a database error occurs
   */
  public function retrieveLastItemId() {
    return $this->itemsDB->retrieveLastItemId();
  }

  // Update methods =======================================================================

  public function updateItem (Item $item) {
    $this->itemsDB->updateItem($item);
  }

  public function updateItemValue ($id_item, $value_item) {
    $this->itemsDB->updateItemValue($id_item, $value_item);
  }

  public function updateCategory (Category $category) {
    $this->categoriesDB->updateCategory($category);
  }

  public function updateUser (PersonRank $user) {
    $this->usersDB->change($user->getEmail(), $user);
  }

  // Delete methods ======================================================================

  /**
   * Deletes all the tables used. For each table, nothing occurs if the table
   * already exists.
   * @throws PDOException if a database error occurs
   */
  public function deleteTables () {
    $this->usersDB->deleteTables();
    $this->categoriesDB->deleteTables();
    $this->itemsDB->deleteTables();
  }

}

?>
