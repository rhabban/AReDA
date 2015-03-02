<?php

require_once("personsRankLib/personRank.php");

/**
 * A utillity class for handling rendering and treatment of forms allowing to create an user
 */
class UserForms {

	/**
	 * Returns a map with default prefilled values for each field relevant to the creation of an user.
	 * @return An associative map fieldName => value (as a string)
	 */
	public static function getDefaultData () {
		 return array(
					  "firstName" => "",
					  "lastName" => "",
					  "email" => "",
					  "password" => "",
					  "rank" => "",
					 );
		}

	/**
	 * Returns 
	 * @return An associative map fieldName => value (as a string)
	 */
	public static function getUserData ($data) {
		 return array(
					  "firstName" => $data->getFirstName(),
					  "lastName" => $data->getLastName(),
					  "rank" => $data->getRank(),
					 );
		}

	/**
	 * Checks validity of data for the creation of an user.
	 * @param $data The input data, as an associative array fieldName => inputValue (as a string)
	 * @return An associative array $fieldName => error (as a human-readable string), where only
	 * field names with errors are set
	 */
	public static function validateUserCreation (array $data, $usersDB) {
		$errors = array();
		if (!isset($data["firstName"]) || $data["firstName"]==="") {
			$errors["firstName"] = "Vous devez entrer un prénom";
		}
		if (!isset($data["lastName"]) || $data["lastName"]==="") {
			$errors["lastName"] = "Vous devez entrer un nom";
		}
		if (!isset($data["email"]) || $data["email"]==="") {
			$errors["email"] = "Vous devez entrer un email";
		}
		if ($usersDB->exists($data["email"]) ) {
			$errors["email"] = "Cette adresse email est déjà utilisée";
		}
		if (!isset($data["password"]) || $data["password"]==="") {
			$errors["password"] = "Vous devez entrer un mot de passe";
		}
		if (!isset($data["rank"]) || $data["rank"]==="") {
			$errors["rank"] = "Vous devez entrer un rang";
		}
		return $errors;
	}

	/**
	 * Checks validity of data for the edition of an user.
	 * @param $data The input data, as an associative array fieldName => inputValue (as a string)
	 * @return An associative array $fieldName => error (as a human-readable string), where only
	 * field names with errors are set
	 */
	public static function validateUserEdition (array $data) {
		$errors = array();
		if (!isset($data["firstName"]) || $data["firstName"]==="") {
			$errors["firstName"] = "Vous devez entrer un prénom";
		}
		if (!isset($data["lastName"]) || $data["lastName"]==="") {
			$errors["lastName"] = "Vous devez entrer un nom";
		}
		if (!isset($data["rank"]) || $data["rank"]==="") {
			$errors["rank"] = "Vous devez entrer un rang";
		}
		return $errors;
	}

	/**
	 * Creates an user from some data. The behaviour is undefined in case the data are not valid, as
	 * decided by the validate method.
	 * @param $data The input data, as an associative map fieldName => value (as a string)
	 * @return An instance of Elimination
	 */
	public static function createUser (array $data) {
		$res = new PersonRank($data["firstName"], $data["lastName"], $data["email"], $data["rank"]);
		return $res;
	}

	/**
	 * Returns the HTML code for elements in which to input data for creating an user.
	 * @param $data Prefilled values for fields, as an associative array fieldName => value (as a string),
	 * optionally set for each field
	 * @return An associative map fieldName => HTML code, where each HTML code is a string, e.g.,
	 * "<input type='text' title='title' />"
	 */
	public static function makeFormElements (array $data) {
		$res = array ();
		$res["lastName"] = "<label>Nom</label>";
		$res["lastName"].= "<input type='text' class='form-control' name='lastName' value='";
		if (isset($data["lastName"])) {
			$res["lastName"] .= htmlspecialchars($data["lastName"]);
		}
		$res["lastName"] .= "' />";

		$res["firstName"] = "<label>Prénom</label>";
		$res["firstName"].= "<input type='text' class='form-control' name='firstName' value='";
		if (isset($data["firstName"])) {
			$res["firstName"] .= htmlspecialchars($data["firstName"]);
		}
		$res["firstName"] .= "' />";

		$res["email"] = "<label>Adresse Email</label>";
		$res["email"].= "<input type='text' class='form-control' name='email' value='";
		if (isset($data["email"])) {
			$res["email"] .= htmlspecialchars($data["email"]);
		}
		$res["email"] .= "' />";

		$res["password"] = "<label>Mot de passe</label>";
		$res["password"].= "<input type='password' class='form-control' name='password' value='";
		if (isset($data["password"])) {
			$res["password"] .= htmlspecialchars($data["password"]);
		}
		$res["password"] .= "' />";

		$res["rank"] = "<div class='radio'>";
		$res["rank"].= "<label>";
		$res["rank"].= "<input type='radio' name='rank' value='utilisateur' ";
		if (isset($data["rank"]) && $data["rank"] == 'utilisateur') {
			$res["rank"] .= "checked ";
		} else {
			$res["rank"] .= "checked ";
		}
		$res["rank"].= "'/>";
		$res["rank"].= "Utilisateur";
		$res["rank"].= "</label>";
		$res["rank"].= "</div>";

		$res["rank"].= "<div class='radio'>";
		$res["rank"].= "<label>";
		$res["rank"].= "<input type='radio' name='rank' value='administrateur' ";
		if (isset($data["rank"]) && $data["rank"] == 'administrateur') {
			$res["rank"] .= "checked ";
		}
		$res["rank"].= "'/>";
		$res["rank"].= "Administrateur";
		$res["rank"].= "</label>";
		$res["rank"].= "</div>";

		return $res;
	}

}

?>
