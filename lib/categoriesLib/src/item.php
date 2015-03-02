<?php

class Item {

  /** The item name */
  protected $name_item;

  /** The value_item */
  protected $value_item;

  /** The category id */
  protected $id_category;

  /** The item id */
  protected $id;


  /**
   * Builds a new instance.
   * @param $item_id The item's id (int)
   * @param $name_item The item's name (string)
   * @param $category_id The category_id (int)
   */
  public function __construct ($name_item, $value_item = "", $id_category, $id=null) {
    $this->name_item = $name_item;
    $this->value_item = $value_item;
    $this->id_category = $id_category;
    $this->id = $id;
  }

  /**
   * Returns the item's name.
   * @return A string
   */
  public function getName () {
    return $this->name_item;
  }

  /**
   * Edit the item's name
   */
  public function setName ($new_Name) {
    $this->name_item = $new_Name;
  }

  /**
   * Returns the item's value_item.
   * @return A string
   */
  public function getValue () {
    return $this->value_item;
  }

  /**
   * Edit the item's value_item.
   */
  public function setValue ($newValue_item) {
    $this->value_item = $newValue_item;
  }

  /**
   * Returns the category id.
   * @return A string
   */
  public function getCategoryId() {
    return $this->id_category;
  }

  /**
   * Edit the category id.
   */
  public function setCategoryId ($newId) {
    $this->id_category = $newId;
  }

  /**
   * Returns the item id.
   * @return A string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Edit the category id.
   */
  public function setId ($newId) {
    //'MAX(`id_item`)' => null
    $newId=$newId['MAX(`id_item`)'];
    if ($newId == null){
      $newId = 1;
    }
    $this->id = $newId;
  }


  /**
   * Returns a representation of this item as a string.
   * @return A representation of this item as a string
   */
  public function __toString () {
    return "nom : " . $this->name_item .", valeur = " . $this->value_item ;
  }

}

?>