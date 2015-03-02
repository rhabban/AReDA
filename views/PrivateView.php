<?php

require_once("control/URLBuilder.php");
require_once("View.php");

/**
 * A class for preparing and rendering pages for the XSports web site when no user is logged in.
 * The basic usage of this class consists of calling one of the "makeXXXPage" methods, then
 * calling the "render" method.
 */
abstract class PrivateView extends View {

	/** The user logged in (as an instance of class Person). */
    protected $user;

    /**
     * Builds a new instance.
     * @param $user The user logged in
     * @param $urlBuilder An object for building URLs
     * @param $feedback The feedback to be displayed (once) to the user
     */
    public function __construct ($user, URLBuilder $urlBuilder, $feedback=null) {
        parent::__construct($urlBuilder, $feedback);
        $this->user = $user;
    }

    /**
     * Prepares a page where users are listed 
     */
    public function makeUserAdministrativeDataPage($userCategories) {
        $this->title = "Tous vos formulaires";
        $this->content.= $this->makeUserAdministrativeDataMenu();
        $this->content.="<h2>" . $this->title . "</h2>";
        $this->content.="<div id='actions'>";
        $this->content.="<a id='edit' class='btn btn-primary btn-lg' data-toggle='tooltip' title data-original-title='Editer' role='button'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>";
        $this->content.="<div id='editionMode'>Edition activé&nbsp;";
        $this->content.="<a id='cancel' class='btn btn-danger btn-lg' data-toggle='tooltip' title data-original-title='Annuler les opérations' role='button'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>&nbsp;";
        $this->content.="<a id='save' href='post.php' class='btn btn-success btn-lg' data-toggle='tooltip' title data-original-title='Sauvegarder' role='button'><span class='glyphicon glyphicon-floppy-saved' aria-hidden='true'></span></a>";
        $this->content.="</div>";
        $this->content.="</div>";
        $this->content.="<div class='row'>";

        foreach ($userCategories as $category){

            $this->content.="<div class='col-md-6'>";
            $this->content.="<table class='table table-hover'>";
            $this->content.="<thead><tr><th>".$category->getName()."</th><th></th></thead>";
            $this->content.="<tbody>";
            
            foreach($category->getItemsList() as $item){
                $this->content.="<tr><td>" .$item->getName() ."</td><td><a href='#' class='value_item' data-type='text' data-pk='" . $item->getId() . "' data-url='' >" . $item->getValue() . "</em></td></tr>";
            }

            $this->content.="</tbody>";
            $this->content.="</table>";
            $this->content.="</div>";
        }
        $this->content.="</div>";    
    }

    /**
     * Prepares a page displaying a form for creating an artwork.
     * @param $data An associative map fieldName => prefilledValue
     * @param $errors An associative map fieldName => inputError (as strings)
     * @param $newArtworkId The id for the new artwork
     */
    public function makeArtworkCreationForm (array $data, array $errors, $newArtworkId) {
      $this->title="Formulaire d'ajout d'&oelig;uvre";
      $this->content.= $this->makeWorksMenu();
      $this->content.="<h2>" . $this->title . "</h2>";
      $this->content.= "<form action='{$this->urlBuilder->getSaveArtWorkURL($newArtworkId)[0]}' method='post'>".PHP_EOL;
      $elements=ArtworkForms::makeFormElements($data);
      $fields=array("title" => "", "method" => "", "date" => "");
      foreach ($fields as $fieldName => $label) {
        if (isset($errors[$fieldName])) {
            $this->content .= "<div class='form-group has-error'>";
            $this->content .= $elements[$fieldName];
            $this->content .= " <span class='control-label'>$errors[$fieldName]</span>";
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
     * Prepares menu to user Administrative data page
     */
    public function makeUserAdministrativeDataMenu(){
        $url = ".?";
        $url.= $_SERVER["QUERY_STRING"];
        $userAdministrativeDataMenu = "<nav>";
        $userAdministrativeDataMenu.= "<ul class='nav nav-tabs'>";
        foreach ($this->getUserAdministrativeDataMenu() as $link) {
            $slug = $link[1];
            $link = $link[0];
            
            if($link == $url){
                $userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else if($link == '.' && $_SERVER["QUERY_STRING"] == ''){
                $userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else if (count(explode('&amp;', $link))>1 && count(explode('&', $url))>1 ){
                $firstChild = explode('&amp;', $link)[1];
                if ($firstChild == explode('&', $url)[1]){
                    $userAdministrativeDataMenu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
                }
            } else{
                $userAdministrativeDataMenu.= "<li><a href=\"".$link."\">".$slug."</a></li>";   
            }
        }
        $userAdministrativeDataMenu.= "</ul>";
        $userAdministrativeDataMenu.= "</nav>";
        return $userAdministrativeDataMenu;
    }


    /**
     * Returns a menu for all pages.
     * @return An associative array $url => $text
     */
    protected function getMenu () {
        return array(
                     $this->urlBuilder->getWelcomeURL(),
                     $this->urlBuilder->getUserAdministrativeDataURL(),
                );
    }

    /**
     * Returns a menu for user Administrative data pages.
     * @return An associative array $url => $text
     */
    protected function getUserAdministrativeDataMenu () {
        return array(
                     $this->urlBuilder->getUserAdministrativeDataURL('Afficher')
            );
    }

    protected function getLogBoxOrSalutation () {
        $url = $this->urlBuilder->getWelcomeURL();
        $res ="";
        $res.="<p>";
        $res.="Connect&eacute; comme ".$this->user->getSalutation();
        $res.=" , rang : ".$this->user->getRank();
        $res.="<form action='".$url[0]."' method='post'>";
        $res.="<input type='hidden' name='login-logout' value='logout'>";
        $res.="<input type='submit' value='D&eacute;connexion' />";
        $res.="</form>";
        $res.="</p>";
        return $res;
    }

}

?>
