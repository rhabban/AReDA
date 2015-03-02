<?php

require_once("control/URLBuilder.php");
require_once("PrivateView.php");

/**
 * A class for preparing and rendering pages for the XSports web site when no user is logged in.
 * The basic usage of this class consists of calling one of the "makeXXXPage" methods, then
 * calling the "render" method.
 */
class AdminView extends PrivateView {

    /**
     * Builds a new instance.
     * @param $user The user logged in
     * @param $urlBuilder An object for building URLs
     * @param $feedback The feedback to be displayed (once) to the user
     */
    public function __construct ($user, URLBuilder $urlBuilder, $feedback=null) {
        parent::__construct($user, $urlBuilder, $feedback);
        $this->user = $user;
        //echo "Vue admin !";
    }

	/**
	 * Prepares a page welcoming the user to the web site (overrides the method in class PublicView).
	 */
    public function makeWelcomePage () {
        $this->title="Bienvenue sur Artiste'Actif";
        $this->content=file_get_contents("fragments/privateWelcome.html");
    }

    /**
     * Prepares a page where users are listed 
     */
    
    public function makeUsersPage($listUsers) {
        $this->title = "Liste des utilisateurs";
        $this->content.= $this->makeAdminUserMenu();
        $this->content.="<h2>" . $this->title . "</h2>";
        $this->content.="<table class='table table-hover'>";
        $this->content.="<thead><tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rang</th><th>Actions</th></tr></thead>";
        $this->content.="<tbody>";
        foreach ($listUsers as $k){
            $this->content.= "<tr><td>". $k->getLastName() . "</td><td>" . $k->getFirstName() . "</td><td>" . $k->getEmail() . "</td><td>" . $k->getRank() ."</td><td>
                <a href='" . $this->urlBuilder->getNewEditUserURL($k->getEmail())[0] . "'><button type='button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></button></a> ";
            if ($k->getEmail() != $_SESSION['user']->getEmail()){
               $this->content.= "<a href='". $this->urlBuilder->getDeleteUserURL($k->getEmail())[0] . "'><button type='button' class='btn btn-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a>";
            }
            $this->content.= "</td></tr>";
        }
        $this->content.="</tbody>";
        $this->content.="</table>";
    }

    /**
     * Prepares a page displaying a form for creating an user.
     * @param $data An associative map fieldName => prefilledValue
     * @param $errors An associative map fieldName => inputError (as strings)
     * @param $newArtworkId The id for the new artwork
     */
    public function makeUserCreationForm (array $data, array $errors, $newUserId) {
      $this->title="Formulaire d'ajout d'un nouvel utilisateur";
      $this->content.= $this->makeAdminUserMenu();
      $this->content.="<h2>" . $this->title . "</h2>";
      $this->content.= "<form action='{$this->urlBuilder->getSaveCreationURL($newUserId)[0]}' method='post'>".PHP_EOL;
      $elements=UserForms::makeFormElements($data);
      $fields=array("lastName" => "Nom", "firstName" => "Prénom", "email" => "Adresse Email", "password" => "", "rank" => "Rang"); //A adapter (pas besoin du label ici)
      foreach ($fields as $fieldName => $label) {
        if (isset($errors[$fieldName])) {
            $this->content .= "<div class='form-group has-error'>";
            $this->content .= $elements[$fieldName];
            $this->content .= " <span class='control-label'><em>$errors[$fieldName]</em></span>";
        } else {
            $this->content .= "<div class='form-group'>";
            $this->content .= $elements[$fieldName];
        }
        $this->content .= "</div>".PHP_EOL;
      }
      $this->content .= "<button type='submit' class='btn btn-default'>Ajouter</button>".PHP_EOL;
      $this->content .= "</form>".PHP_EOL;
    }

    /**
     * Prepares a page displaying a form for editing an user.
     * @param $data An associative map fieldName => prefilledValue
     * @param $errors An associative map fieldName => inputError (as strings)
     * @param $newArtworkId The id for the new artwork
     */
    public function makeUserEditForm (array $data, array $errors, $newUserId) {
      $this->title = "Formulaire d'édition d'un utilisateur";
      $this->content.="<h2>" . $this->title . "</h2>";
      $this->content.= "<form action='". $this->urlBuilder->getSaveUserEditionURL($newUserId, $_GET["email"])[0] . "' method='post'>".PHP_EOL;
      $elements = UserForms::makeFormElements($data);
      $fields=array("lastName" => "Nom", "firstName" => "Prénom", "rank" => "Rang"); //A adapter (pas besoin du label ici)
      foreach ($fields as $fieldName => $label) {
        if (isset($errors[$fieldName])) {
            $this->content .= "<div class='form-group has-error'>";
            $this->content .= $elements[$fieldName];
            $this->content .= " <span class='control-label'><em>$errors[$fieldName]</em></span>";
        } else {
            $this->content .= "<div class='form-group'>";
            $this->content .= $elements[$fieldName];
        }
        $this->content .= "</div>".PHP_EOL;
      }
      $this->content .= "<button type='submit' class='btn btn-default'>Editer</button>".PHP_EOL;
      $this->content .= "</form>".PHP_EOL;
    }

    /**
     * Prepares menu to users page
     */
    public function makeAdminUserMenu(){
        $url = ".?";
        $url.= $_SERVER["QUERY_STRING"];
        $adminUser = "<nav>";
        $adminUser.= "<ul class='nav nav-tabs'>";
        foreach ($this->getAdminUserMenu() as $link) {
            $slug = $link[1];
            $link = $link[0];
            
            if($link == $url){
                $adminUser.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else if($link == '.' && $_SERVER["QUERY_STRING"] == ''){
                $adminUser.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else if (count(explode('&amp;', $link))>1 && count(explode('&', $url))>1 ){
                $firstChild = explode('&amp;', $link)[1];
                if ($firstChild == explode('&', $url)[1]){
                    $adminUser.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
                }
            } else{
                $adminUser.= "<li><a href=\"".$link."\">".$slug."</a></li>";   
            }
        }
        $adminUser.= "</ul>";
        $adminUser.= "</nav>";
        return $adminUser;
    }


	/**
     * Returns a menu for all pages.
     * @return An associative array $url => $text
     */
    protected function getMenu () {
        return array(
                     $this->urlBuilder->getWelcomeURL(),
                     $this->urlBuilder->getUserAdministrativeDataURL(),
                     $this->urlBuilder->getUsersURL(),
		    );
    }

    /**
     * Returns a menu for users pages.
     * @return An associative array $url => $text
     */
    protected function getAdminUserMenu () {
        return array(
                     $this->urlBuilder->getUsersURL('Afficher'),
                     $this->urlBuilder->getNewAddUserURL(),
            );
   }
}

?>
