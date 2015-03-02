<?php

/**
 * A class providing methods for building URLs for the "minimal" web site.
 * As a general rule, the URLs are built using HTML entities, as needed in
 * HTML code; htmlspecialchars_decode must be used when the URLs are used for
 * redirections.
 */
class URLBuilder {

    /**
     * Returns the URL of the welcome page.
     * @return A string
     */
    public function getWelcomeURL($linkName = "Accueil") {
        return array(".", $linkName);
    }

    /**
     * Returns the URL of the information page.
     * @param $char The character about which to display information
     * @return A string
     */
    public function getInformationURL ($char, $linkName = "Information") {
        return array(".?action=information&char=$char", $linkName);
    }
    
     /**
     * Returns the URL of the users page.
     * @return A string
     */
    public function getUsersURL ($linkName = "Utilisateurs") {
        return array(".?action=utilisateurs", $linkName);
    }

    /**
     * Returns the URL of the page allowing to create an user.
     * @param $newArworkId The id of the user under construction
     * @return an array
     */
    public function getNewAddUserURL ($linkName = "Ajouter") {
        return array(".?action=utilisateurs&amp;p=add", $linkName);
    }

    /**
     * Returns the URL of the page allowing to create an artwork.
     * @param $newArworkId The id of the artwork under construction
     * @return an array
     */
    public function getAddUserURL ($newUserId, $linkName = "Ajouter") {
        return array(".?action=utilisateurs&amp;p=add&amp;newUserId=$newUserId", $linkName);
    }

    /**
     * Returns the URL of the page allowing to save an elimination tournament.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getSaveCreationURL ($newUserId, $email="", $linkName = "Sauver") {
        return array(".?action=utilisateurs&amp;p=saveCreation&amp;newUserId=$newUserId", $linkName);
    }

    /**
     * Returns the URL of the page allowing to save an elimination tournament.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getSaveUserEditionURL ($newUserId, $email="", $linkName = "Sauver") {
        return array(".?action=utilisateurs&amp;p=saveEdition&amp;email=" .$email."&amp;newUserId=$newUserId", $linkName);
    }

    /**
     * Returns the URL of the page allowing to delete an user.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getDeleteUserURL ($userEmail, $linkName = "Supprimer") {
      return array(".?action=utilisateurs&amp;p=delete&amp;email=$userEmail", $linkName);
    }

    /**
     * Returns the URL of the page allowing to edit an user.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getNewEditUserURL ($userEmail, $linkName = "Supprimer") {
      return array(".?action=utilisateurs&amp;p=edit&amp;email=$userEmail", $linkName);
    }

        /**
     * Returns the URL of the page allowing to edit an user.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getEditUserURL ($userId, $userEmail, $linkName = "Supprimer") {
      return array(".?action=utilisateurs&amp;p=edit&amp;email=$userEmail&amp;newUserId=$userId", $linkName);
    }


     /**
     * Returns the URL of the works page.
     * @return A string
     */
    public function getUserAdministrativeDataURL ($linkName = "Formulaires") {
        return array(".?action=formulaire", $linkName);
    }

    /**
     * Returns the URL of the page allowing to create an artwork.
     * @param $newArworkId The id of the artwork under construction
     * @return an array
     */
    public function getNewAddArtworkURL ($linkName = "Ajouter") {
        return array(".?action=oeuvres&amp;p=add", $linkName);
    }

    /**
     * Returns the URL of the page allowing to create an artwork.
     * @param $newArworkId The id of the artwork under construction
     * @return an array
     */
    public function getAddArtworkURL ($artworkId, $linkName = "Ajouter") {
        return array(".?action=oeuvres&amp;p=add&amp;newArtworkId=$artworkId", $linkName);
    }

    /**
     * Returns the URL of the page allowing to save an elimination tournament.
     * @param $newTournamentId The id of the tournament to save
     * @return an array
     */
    public function getSaveArtworkURL ($artworkId, $linkName = "Sauver") {
      return array(".?action=oeuvres&amp;p=save&amp;newArtworkId=$artworkId", $linkName);
    }
    
    /**
     * Returns the URL of a page explaining that the given login and password are invalid.
     * @return A string
     */
    public function getBadLoginURL () {
        return ".?action=badLogin";
    }

}

?>
