<?php

/**
 * A basic class for representing a person with an email.
 * @author Bruno Zanuttini, Universit&eacute; de Caen Basse-Normandie, France
 * @since March, 2012
 */
class Person {

  /** The person's first name. */
  protected $firstName;

  /** The person's last name. */
  protected $lastName;

  /** The person's email address. */
  protected $email;

  /**
   * Builds a new instance.
   * @param $firstName The person's first name (string)
   * @param $lastName The person's last name (string)
   * @param $email The person's email address (string)
   */
  public function __construct ($firstName, $lastName, $email) {
    $this->firstName=$firstName;
    $this->lastName=$lastName;
    $this->email=$email;
  }

  /**
   * Returns the person's first name.
   * @return A string
   */
  public function getFirstName () {
    return $this->firstName;
  }

  /**
   * Returns the person's last name.
   * @return A string
   */
  public function getLastName () {
    return $this->lastName;
  }

  /**
   * Returns a standard salutation for this person ("first-name last-name")
   * @return A string
   */
  public function getSalutation () {
    return $this->firstName." ".$this->lastName;
  }

  /**
   * Returns the person's email address.
   * @return A string
   */
  public function getEmail () {
    return $this->email;
  }

  /**
   * Returns a representation of this person as a string.
   * @return A representation of this person as a string
   */
  public function __toString () {
    return $this->firstName." ".$this->lastName.", email ".$this->email;
  }

}

?>
