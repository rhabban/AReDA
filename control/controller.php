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
            $categories = $dbHandler->retrieveCategoriesByEmail($user->getEmail());
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

                    case "saveCreation":
                        var_dump($_POST);
                        $data = $_POST;
                        $errors = array(); // A MODIFIER
                        $list_item=array();
                        if (count($errors)==0) {
                            foreach ($data as $key => $value) {
                                if ($key=="name_category") {
                                    $name_category=$value;
                                } else {
                                    $id=explode('_',$key)[2];
                                    $type=explode('_',$key)[0];
                                    if($type=="name"){
                                        $name=$value;
                                    } elseif($type=="value"){
                                        $list_item[$name]=$value;
                                    }
                                }
                            }
                            $newCategory=$dbHandler->createCategory($name_category,$user->getEmail());
                            echo('Category created : '.$name_category);
                            foreach($list_item as $key => $value){
                                $dbHandler->createItem(new Item($key,$value,$newCategory->getId()));    
                                echo ('Item created : '. $key. '-'. $value);
                            };
                            $_SESSION["feedback"] = "<div class='alert alert-info'>L'utilisateur a bien &eacute;t&eacute; ajout&eacute;</div>";
                            header("Location: ".htmlspecialchars_decode($urlBuilder->getUserAdministrativeDataURL()[0]));
                        } else { // count(errors) != 0
                            $_SESSION["feedback"] = "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Il y a des erreurs dans le formulaire !</div>";
                            header("Location: ".htmlspecialchars_decode($urlBuilder->getUserAdministrativeDataURL()[0]));
                        }
                        break;

                    case "saveEdition":  /*
                        Script for update record from X-editable.
                        */
                        //delay (for debug only)
                        sleep(1); 
                        /*
                        You will get 'pk', 'name' and 'value' in $_POST array.
                        */
                        $name = $_POST['name'];
                        $pk = $_POST['pk'];
                        $value = $_POST['value'];
                        /*
                        Check submitted value
                        */
                        if(!empty($value)) {
                            /*
                            If value is correct you process it (for example, save to db).
                            In case of success your script should not return anything, standard HTTP response '200 OK' is enough.

                            for example:
                            $result = mysql_query('update users set '.mysql_escape_string($name).'="'.mysql_escape_string($value).'" where user_id = "'.mysql_escape_string($pk).'"');
                            */

                            //here, for debug reason we just return dump of $_POST, you will see result in browser console
                            echo $value;
                            $dbHandler->updateItemValue($pk,$value);
                        } else {
                        /* 
                            In case of incorrect value or error you should return HTTP status != 200. 
                            Response body will be shown as error message in editable form.
                            */
                            //header('HTTP 400 Bad Request', true, 400);
                            echo "This field is required!";
                        }
                        break;

                    default:
                        $view->makeUserAdministrativeDataPage($categories);
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
