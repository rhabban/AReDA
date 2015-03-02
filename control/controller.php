<?php

require_once("control/authentication.php");
require_once("control/URLBuilder.php");
require_once("model/userForm.php");
require_once("model/AdministrativeDataForm.php");
require_once("views/View.php");
require_once("views/PrivateView.php");
require_once("views/User_View.php");
require_once("views/Admin_View.php");

session_start();

$urlBuilder = new URLBuilder();

// newItemId is the item allocated to a new object, so that several new objects can
// be created in parallel without the corresponding data clashing
// Each id is used once (newItemId is incremented each time)
if (!isset($_SESSION["newItemIds"])) {
  $_SESSION["newItemIds"]=0;
}

// Retrieving current user, if any
handleLoginOrLogout($usersDB,$_POST,$urlBuilder);
$user = isset($_SESSION["user"])? $_SESSION["user"]: null;

// Retrieving and cleaning feedback, if any
$feedback = isset($_SESSION["feedback"])? $_SESSION["feedback"]: null;
unset($_SESSION['feedback']);
// Creating view
$view=new View ($urlBuilder, $feedback);
if ($user===null){
    $view=new View ($urlBuilder, $feedback);
}else {
    switch ($user->getRank()) {

        case "utilisateur":
            $view=new UserView($user,$urlBuilder, $feedback);
            break;

        case "administrateur":
            $view=new AdminView($user,$urlBuilder, $feedback);
            break;

    }
}
// Retrieving required page and parameters
$action = isset($_GET["action"])? $_GET["action"]: null;
$p = isset($_GET["p"])? $_GET["p"]: null;
$char = isset($_GET["char"])? $_GET["char"]: null;
if ($action===null) {
    // Default action is "information" if some character is given, "welcome" otherwise
    $action = ($char===null)? "welcome": "information";
}

// Delivering requested page
switch ($action) {

    case "welcome":
        $view->makeWelcomePage();
        break;
        
	case "utilisateurs":
        $accessRank = array("administrateur");
        if($user != null){
            if(in_array($user->getRank(), $accessRank)){
                if($p===null && substr($_SERVER["QUERY_STRING"], -1) != "&"){
                    $view->makeUsersPage($usersDB->retrieveAll());
                } else {
                    switch ($p) {
                        case 'add':
                            if(!isset($_GET['newUserId'])){
                                // First requesting creation of new user
                                $data = UserForms::getDefaultData();
                                $errors = array();
                                $newUserId=$_SESSION["newItemIds"]++;
                                $view->makeUserCreationForm($data, $errors, $newUserId);
                            } else {
                                $newUserId=$_GET["newUserId"];
                                if (!isset($_SESSION["newUser"][$newUserId])) {
                                    // First requesting creation of new user
                                    $data = UserForms::getDefaultData();
                                    $errors = array();
                                    $view->makeUserCreationForm($data, $errors, $newUserId);
                                } else {
                                    // Correcting data for new user
                                    $data = $_SESSION["newUser"][$newUserId];
                                    $errors = $_SESSION["newUserErrors"][$newUserId];
                                    $view->makeUserCreationForm($data,$errors,$newUserId);
                                }
                            }
                            break;

                        case "saveCreation":
                            $newUserId=$_GET["newUserId"];
                            $data = $_POST;
                            $errors = UserForms::validateUserCreation($data, $usersDB);
                            if (count($errors)==0) {
                                $user = UserForms::createUser($data);
                                unset($_SESSION["newUser"][$newUserId]);
                                unset($_SESSION["newUserErrors"][$newUserId]);
                                header("HTTP/1.1 303 See Other");
                                // VERIFIER SI LA CLE EMAIL N'EST PAS DEJA EXISTANTE SINON BUG !
                                $dbHandler->createUser($user, $data['password']);
                                $_SESSION["feedback"] = "<div class='alert alert-info'>L'utilisateur a bien &eacute;t&eacute; ajout&eacute;</div>";
                                header("Location: ".htmlspecialchars_decode($urlBuilder->getUsersURL()[0]));
                            } else { // count(errors) != 0
                                $_SESSION["newUser"][$newUserId] = $data;
                                $_SESSION["newUserErrors"][$newUserId] = $errors;
                                $_SESSION["feedback"] = "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Il y a des erreurs dans le formulaire !</div>";
                                header("Location: ".htmlspecialchars_decode($urlBuilder->getAddUserURL($newUserId)[0]));
                            }
                            break;

                        case "saveEdition":
                            $newUserId=$_GET["newUserId"];
                            $data = $_POST;
                            $errors = UserForms::validateUserEdition($data);
                            if (count($errors)==0) {
                                $oldData = $usersDB->retrieve($_GET["email"]);
                                $data['email'] = $oldData->getEmail();
                                $user = UserForms::createUser($data);
                                unset($_SESSION["newUser"][$newUserId]);
                                unset($_SESSION["newUserErrors"][$newUserId]);
                                $dbHandler->updateUser($user, $data['email']);
                                $_SESSION["feedback"] = "<div class='alert alert-warning'>Informations de l'utilisateur modifiées :<br/>Nom : ".$user->getLastName().",</br>Prénom : ".$user->getFirstName().",<br/> Rang : ".ucfirst($user->getRank()).".</div>";
                                header("Location: ".htmlspecialchars_decode($urlBuilder->getUsersURL()[0]));
                            } else { // count(errors) != 0
                                $_SESSION["editUser"][$newUserId] = $data;
                                $_SESSION["editUserErrors"][$newUserId] = $errors;
                                $_SESSION["feedback"] = "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Il y a des erreurs dans le formulaire !</div>";
                                header("HTTP/1.1 303 See Other");
                                header("Location: ".htmlspecialchars_decode($urlBuilder->getEditUserURL($newUserId,$_GET['email'])[0]));
                            }
                            break;

                        case "delete":
                            $usersDB->delete($_GET['email']);
                            $_SESSION["feedback"] = "<div class='alert alert-info'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> L'utilisateur à bien été supprimé</div>";
                            header("Location: ".htmlspecialchars_decode($urlBuilder->getUsersURL()[0]));
                            break;

                        case "edit":
                            if(!isset($_GET['newUserId'])){
                                // First requesting editing an user
                                $data = UserForms::getUserData($usersDB->retrieve($_GET['email']));
                                $errors = array();
                                $newUserId=$_SESSION["newItemIds"]++;
                                $view->makeUserEditForm($data, $errors, $newUserId);
                            } else {
                                $newUserId=$_GET["newUserId"];
                                if (!isset($_SESSION["editUser"][$newUserId])) {
                                    // First requesting editing an user
                                    $data = UserForms::getUserData($usersDB->retrieve($_GET['email']));
                                    $errors = array();
                                    $view->makeUserEditForm($data, $errors, $newUserId);
                                } else {
                                    // Correcting data for user
                                    $data = UserForms::getUserData($usersDB->retrieve($_GET['email']));
                                    $errors = $_SESSION["editUserErrors"][$newUserId];
                                    $view->makeUserEditForm($data,$errors,$newUserId);
                                }
                            }
                        break;
                        
                        default:
                             $view->makeUsersPage($usersDB->retrieveAll());
                            break;
                    }
                }
            } else {
                $view->makeAccessDeniedPage($action);
            }
        } else {
             $view->makeAccessDeniedPage($action);
        }
        break;

    case "formulaire":
        if($user != null){
            if($p===null && substr($_SERVER["QUERY_STRING"], -1) != "&"){
                $categories = $dbHandler->retrieveCategoriesByEmail($user->getEmail());
                $items= array();
                foreach($categories as $category){
                    $dbHandler->retrieveItemsByCategory($category->getId());
                    $category->setItemsList($dbHandler->retrieveItemsByCategory($category->getId()));
                }
                $view->makeUserAdministrativeDataPage($categories);
            } else {
                switch($p) {
                    case "add":
                        if($user->getRank() == "administrateur" || $user->getRank() == "artiste"){
                            if(!isset($_GET['newArtworkId'])){
                                // First requesting creation of new artwork
                                $data = ArtWorkForms::getDefaultData();
                                $errors = array();
                                $newArtworkId=$_SESSION["newItemIds"]++;
                                $view->makeArtworkCreationForm($data, $errors, $newArtworkId);
                            } else {
                                $newArtworkId=$_GET["newArtworkId"];
                                if (!isset($_SESSION["newArtwork"][$newArtworkId])) {
                                  // First requesting creation of new artwork
                                  $data = ArtWorkForms::getDefaultData();
                                  $errors = array();
                                  $view->makeArtworkCreationForm($data, $errors, $newArtworkId);
                                } else {
                                  // Correcting data for new artwork
                                    $data = $_SESSION["newArtwork"][$newArtworkId];
                                    $errors = $_SESSION["newArtworkErrors"][$newArtworkId];
                                    $view->makeArtworkCreationForm($data,$errors,$newArtworkId);
                                }
                            }
                        }else{
                            header("Location:index.php");
                            $_SESSION["feedback"] = "<div class='alert alert-info'>Vous n'avez pas l'autorisation d'ajouter une &oelig;uvre !</div>";
                        }
                        break;

                    case "save":
                        $newArtworkId=$_GET["newArtworkId"];
                        $data = $_POST;
                        $errors = ArtWorkForms::validateArtworkCreation($data,$ownersDB);
                        if (count($errors)==0) {
                            $artwork = ArtWorkForms::createArtwork($data);
                            $user = $_SESSION['user'];
                            $ownersDB->create($user, $artwork);
                            unset($_SESSION["newArtwork"][$newArtworkId]);
                            unset($_SESSION["newArtworkErrors"][$newArtworkId]);
                            header("HTTP/1.1 303 See Other");
                            $artworkDB->create($artwork);
                            $_SESSION["feedback"] = "<div class='alert alert-info'>L'&oelig;uvre a bien &eacute;t&eacute; ajout&eacute;e</div>";
                            header("Location: ".htmlspecialchars_decode($urlBuilder->getAllWorksURL()[0]));
                        } else { // count(errors) != 0
                            $_SESSION["newArtwork"][$newArtworkId] = $data;
                            $_SESSION["newArtworkErrors"][$newArtworkId] = $errors;
                            $_SESSION["feedback"] = "<div class='alert alert-danger'>Il y a des erreurs dans le formulaire</div>";
                            header("HTTP/1.1 303 See Other");
                            header("Location: ".htmlspecialchars_decode($urlBuilder->getAddArtworkURL($newArtworkId)[0]));
                        }
                        break;

                    default:
                        header("Location: ".htmlspecialchars_decode($urlBuilder->getAllWorksURL()[0]));
                        break;
                }
            }
        } else {
            $view->makeAccessDeniedPage($action);
        }
        break;

    default:
        $view->makeUnknownActionPage($action);

}

// Rendering page
$view->render();

?>
