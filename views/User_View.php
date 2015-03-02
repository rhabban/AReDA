<?php

require_once("control/URLBuilder.php");
require_once("PrivateView.php");

/**
 * A class for preparing and rendering pages for the XSports web site when no user is logged in.
 * The basic usage of this class consists of calling one of the "makeXXXPage" methods, then
 * calling the "render" method.
 */
class UserView extends PrivateView {
    
    /**
     * Builds a new instance.
     * @param $user The user logged in
     * @param $urlBuilder An object for building URLs
     * @param $feedback The feedback to be displayed (once) to the user
     */
    public function __construct ($user, URLBuilder $urlBuilder, $feedback=null) {
        parent::__construct($user, $urlBuilder, $feedback);
        $this->user = $user;
        //echo "Vue utilisateur !";
    }

	/**
	 * Prepares a page welcoming the user to the web site (overrides the method in class PublicView).
	 */
    public function makeWelcomePage () {
        $this->title="Bienvenue sur Artiste'Actif";
        $this->content=file_get_contents("fragments/privateWelcome.html");
    }

}

?>
