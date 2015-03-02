<?php

require_once("control/URLBuilder.php");
require_once("config/db.php");

/**
 * Handles login and logout actions from given data. Redirects the user to a "bad login" page in case a
 * login attempt fails. Otherwise, on successful login stores the user (as an instance of Person) and its tournaments
 * (as a map id => tournament) in $_SESSION["user"] and $_SESSION["tournaments"], and on successful logout
 * sets $_SESSION["user"] to null.
 * @param $dbHandler A database handler (for users)
 * @param $data The input to the action (typically, $_POST), as a map "login-logout" => "login" | "logout",
 * and for login, "email" and "password" as entered by the user
 * @param $urlBuilder An object for building URLs
 */
function handleLoginOrLogout (PersonsRankDB $dbPerson, array $data, URLBuilder $urlBuilder) {

    if (isset($data["login-logout"])) {

        $action = $data["login-logout"];
        $_SESSION["user"] = null;

        switch ($action) {

            case "login":
                if (!isset($data["email"],$data["password"]) || !$dbPerson->isValid($data["email"], $data["password"])) {
                    header("Location: " . htmlspecialchars_decode($urlBuilder->getBadLoginURL()));
                    return;
                }
                // Successful login
                $_SESSION["user"] = $dbPerson->retrieve($data["email"]);
                $_SESSION["feedback"] = "<div class='alert alert-success'>Connexion r&eacute;ussie</div>";
                break;

            case "logout":
                $_SESSION["feedback"] = "<div class='alert alert-info'>Vous &ecirc;tes maintenant d&eacute;connect&eacute;</div>";
                break;

            }

       }

}

?>
