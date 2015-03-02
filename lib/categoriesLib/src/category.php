<?php

class Category {

  /** The category name */
  protected $name_category;

  /** The category id */
  protected $id_category;

  /** The user email */
  protected $email_user;

  /** The items list */
  protected $list_item;

  /**
   * Builds a new instance.
   * @param $category_id The category's id (int)
   * @param $category_name The category's name (string)
   * @param $listItems The list items (list class Item)
   */
  public function __construct ($name_category, $email_user, $id_category = null) {
    $this->name_category = $name_category;
    $this->email_user = $email_user;
    $this->id_category = $id_category;
  }

  /**
   * Returns the category's name.
   * @return A string
   */
  public function getName () {
    return $this->name_category;
  }

  /**
   * Edit the category's name.
   */
  public function setName ($newName_category) {
    $this->name_category = $newName_category;
  }

  /**
   * Return the category's id.
   */
  public function getId () {
    return $this->id_category;
  }

  /**
   * Edit the category's id.
   */
  public function setId ($newId) {
    //'MAX(`id_category`)' => null
    if ($newId == null){
      $newId = 1;
    }
    $this->id_category = $newId;
  }

  /**
   * Returns the category's name.
   * @return A string
   */
  public function getUserEmail () {
    return $this->email_user;
  }

  /**
   * Edit the user Email.
   */
  public function setUserEmail($newEmail_user){
    $this->email_user = $newEmail_user;
  }

  /**
   * Return the items list.
   */
  public function getItemsList () {
    return $this->list_item;
  }

  /**
   * Return the items list.
   */
  public function setItemsList ($list_item) {
    $this->list_item = $list_item;
  }

  /**
   * Returns a representation of this category as a string.
   * @return A representation of this category as a string
   */
  public function __toString () {
    return "nom : " . $this->name_category;
  }

}

?>
