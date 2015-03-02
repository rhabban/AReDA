<?php

require_once("categoriesLib/src/item.php");
require_once("categoriesLib/src/category.php");

/**
 * A utillity class for handling rendering and treatment of forms allowing to create administrative data form
 */
class AdministrativeDataForm {

	/**
	 * Returns a map with default prefilled values for each field relevant to the creation of an artwork.
	 * @return An associative map fieldName => value (as a string)
	 */
	public static function getDefaultData () {
		 return array(
					  "title" => "",
					  "method" => "",
					  "date" => "",
					 );
	}

	/**
	 * Returns 
	 * @return An associative map fieldName => value (as a string)
	 */
	public static function getUserData ($data) {
		$categories= array();
		foreach($data as $category){
			$categories[]= array(
					  "name_category"=> $category->getName(),
					  "id_category" => $category->getId(),
					 );
			}
		return $categories;
	}

	/**
	 * Checks validity of data for the creation of an artwork.
	 * @param $data The input data, as an associative array fieldName => inputValue (as a string)
	 * @return An associative array $fieldName => error (as a human-readable string), where only
	 * field names with errors are set
	 */
	public static function validateArtworkCreation (array $data) {
		$errors = array();
		if (!isset($data["title"]) || $data["title"]==="") {
			$errors["title"] = "Vous devez entrer un titre";
		}
		if (!isset($data["date"]) || $data["date"]==="") {
			$errors["date"] = "Vous devez entrer une date";
		}
		if (!isset($data["method"]) || $data["method"]==="") {
			$errors["method"] = "Vous devez entrer une méthode";
		}
		return $errors;
	}

	/**
	 * Creates an artwork from some data. The behaviour is undefined in case the data are not valid, as
	 * decided by the validate method.
	 * @param $data The input data, as an associative map fieldName => value (as a string)
	 * @return An instance of Elimination
	 */
	public static function createArtwork (array $data) {
		$res = new Work($data["title"], $data["method"], $data["date"]);
		return $res;
	}

	/**
	 * Returns the HTML code for elements in which to input data for creating an artwork.
	 * @param $data Prefilled values for fields, as an associative array fieldName => value (as a string),
	 * optionally set for each field
	 * @return An associative map fieldName => HTML code, where each HTML code is a string, e.g.,
	 * "<input type='text' title='title' />"
	 */
	public static function makeFormElements (array $data) {
		$res = array ();
		$res["title"] = "<label>Titre de l'&oelig;uvre</label>";
		$res["title"].= "<input type='text' . class='form-control' name='title' value='";
		if (isset($data["title"])) {
			$res["title"] .= htmlspecialchars($data["title"]);
		}
		$res["title"] .= "' />";

		$res["date"] = "<label>Date de réalisation</label>";
		$res["date"].= "<input type='text' . class='form-control' name='date' value='";
		if (isset($data["date"])) {
			$res["date"] .= htmlspecialchars($data["date"]);
		}
		$res["date"] .= "' />";

		$res["method"] = "<label>Méthode utilisée</label>";
		$res["method"].= "<input type='text' . class='form-control' name='method' value='";
		if (isset($data["method"])) {
			$res["method"] .= htmlspecialchars($data["method"]);
		}
		$res["method"] .= "' />";

		return $res;
	}

}

?>
