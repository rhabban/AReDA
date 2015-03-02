<?php

require_once("control/URLBuilder.php");

/**
 * A class for preparing and rendering pages for the XSports web site when no user is logged in.
 * The basic usage of this class consists of calling one of the "makeXXXPage" methods, then
 * calling the "render" method.
 */
class View {

    /** An object for building URLs. */
    protected $urlBuilder;

    /** The title for this page (prepared by the "makeXXXPage" methods). */
    protected $title;

    /** The main content for this page (prepared by the "makeXXXPage" methods). */
    protected $content;

     /** The feedback for this page (prepared by the "makeXXXPage" methods). */
    protected $feedback;

    // Generic methods ========================================================================

    /**
     * Builds a new instance.
     * @param $urlBuilder An object for building URLs
     * @param $feedback The feedback to be displayed (once) to the user
     */
    public function __construct(URLbuilder $urlBuilder, $feedback=null) {
        $this->urlBuilder = $urlBuilder;
        $this->feedback = $feedback;
		$this->title = null;
		$this->content = null;
    }

    /**
     * Renders the prepared page. If no page has been prepared, then makeUnexpectedErrorPage() is called first.
     */
    public function render () {
        if ($this->title===null || $this->content===null) {
            $this->makeUnexpectedErrorPage(new Exception ("Tried to render a view with null title or content"));
        }
        // Now $this->title and $this->content are nonnull
        $title = $this->title;
        //$content = getBreadcrumbs();
        $content= $this->content;
        if ($this->feedback !== null) {
            $feedback = "<div class='feedback'>".$this->feedback."</div>";
        }else {
			$feedback = null;
		}
        $logBox = $this->getLogBoxOrSalutation();
        include("template.php");
    }

    // Methods for preparing specific pages ========================================================

    /**
     * Prepares a page welcoming the user to the web site.
     */
    public function makeWelcomePage () {
        $this->title = "Site d'information sur les lettres";
        $this->content = file_get_contents("fragments/welcome.html");
    }

    /**
     * Prepares a page showing information about a given character.
     * @param $char A character
     * @param $ord The rank of $char in the alphabet
     */
    public function makeInformationPage ($char, $ord) {
        $this->title = "Information sur la lettre '".$char."'";
        $ordAsString = ($ord==1? "1re": "${ord}e");
        $this->content = "<p>La lettre '".$char."' est la ${ordAsString} lettre de l'alphabet.</p>";
    }

    /**
     * Prepares a page explaining that the required action does not exist.
     * @param $action The unknown action
     */
    public function makeUnknownActionPage ($action) {
        $this->title="Erreur";
        $this->content=file_get_contents("fragments/unknownAction.html");
    }

    /**
     * Prepares a page explaining that user is not authorized to access the page.
     * @param $action The unknown action
     */
    public function makeAccessDeniedPage ($action) {
        $this->title="Accès refusé";
        $this->content=file_get_contents("fragments/accessDenied.html");
    }

    /**
     * Prepares menu to all page
     */
    public function makeMenu(){
        if(!isset($_GET)){
            $url =".";
        } else if(count($_GET) == 1){
            $url = ".?" . $_SERVER["QUERY_STRING"];
        } else{
            $res = explode("&", $_SERVER["QUERY_STRING"]);
            $url =".?" . $res[0];
        }

        $menu ='<nav class="navbar navbar-inverse navbar-fixed-top">';
        $menu.='<div class="container-fluid">';
        $menu.='<div class="navbar-header">';
        $menu.='<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">';
        $menu.='<span class="sr-only">Toggle navigation</span>';
        $menu.='<span class="icon-bar"></span>';
        $menu.='<span class="icon-bar"></span>';
        $menu.='<span class="icon-bar"></span>';
        $menu.='</button>';
        $menu.='</div>';
        $menu.='<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
        $menu.='<ul class="nav navbar-nav">';
        foreach ($this->getMenu() as $link) {
            $slug = $link[1];
            $link = $link[0];
            if($link == $url){
                $menu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else if($link == '.' && $_SERVER["QUERY_STRING"] == ''){
                $menu.= "<li class='active'><a href=\"".$link."\">".$slug."</a></li>";
            } else{
                $menu.= "<li><a href=\"".$link."\">".$slug."</a></li>";   
            }
        }
        $menu.= "</ul>";
        $menu.= "</div>";
        $menu.= "</div>";
        $menu.= "</div>";
        $menu.= "</nav>";

        return $menu;
    }

    /**
     * Returns a menu for all pages.
     * @return An associative array $url => $text
     */
    protected function getMenu () {
        return array(
                     $this->urlBuilder->getWelcomeURL(),
                     $this->urlBuilder->getInformationURL('a', "information sur la lettre 'a'"),
                     $this->urlBuilder->getInformationURL('b', "information sur la lettre 'b'")
                     );
    }

     /**
     * Prepares Breadcrumbs to all page
     *
     */
    public function makeBreadcrumbs(){
        $menu ='<ol class="breadcrumb">';
        if(isset($_GET)){
            foreach($_GET as $key => $value){
                $menu.='';
            }
        }
        /*
        if(!isset($_GET)){
            $url=".";
        } else if(count($_GET) == 1){
            $url = ".?" . $_SERVER["QUERY_STRING"];
        } else{
            $url=".?" . explode("&", $_SERVER["QUERY_STRING"])[0];
        }
        $menu ='<ol class="breadcrumb">';
        foreach ($this->getBreadcrumbs() as $link => $text) {
            if($link == $url){
                $menu.= "<li class='active'><a href=\"".$link."\">".$text."</a></li>";
            } else if($link == '.' && $_SERVER["QUERY_STRING"] == ''){
                $menu.= "<li class='active'><a href=\"".$link."\">".$text."</a></li>";
            } else{
                $menu.= "<li><a href=\"".$link."\">".$text."</a></li>";   
            }
        }
        $menu.= "</ol>";

        return $menu;
        */
    }

    /**
     * Returns a breadcrumbs for all pages.
     * @return An associative array $url => $text
     */
    protected function getBreadcrumbs () {
        $linksBreadcrumbs = Array();
        if(isset($_GET)){
            foreach($_GET as $key => $value){
                $linksBreadcrumbs[] = $key;
            }
        }
                
        return $linksBreadcrumbs;
    }


    protected function getLogBoxOrSalutation(){
    	$url = $this->urlBuilder->getWelcomeURL();
    	return "<form class='form-inline' method='post' action='{$url[0]}'>".PHP_EOL.file_get_contents("fragments/logBox.html").PHP_EOL."</form>";
    }

}

?>
