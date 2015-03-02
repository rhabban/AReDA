<?php

include("config/config.php");
include("personsRankLib/personRank.php");
include("personsRankLib/personRankDB.php");
include("model/OwnersDB.php");
include("model/DBHandler.php");

$pdo=new PDO("mysql:host=".HOST.";port=".PORT.";dbname=".DB,USER,PWD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_PERSISTENT => true));
$dbHandler = new DBHandler ($pdo);
$usersDB = $dbHandler->retrieveAllUsers();

?>
