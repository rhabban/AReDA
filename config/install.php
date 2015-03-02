<?php

set_include_path(get_include_path().PATH_SEPARATOR."../lib".PATH_SEPARATOR."..");
include("db.php");

$dbHandler->deleteTables();
$dbHandler->createTables();

$mathieu = new PersonRank("Mathieu", "Monnier", "emailMathieu@mail.com", "adherent");
$corentin = new PersonRank("Corentin", "Chupin", "emailCorentin@mail.com", "artiste");
$admin = new PersonRank("Admin", "Admin", "admin", "administrateur");

$dbHandler->createUser($mathieu, "mamat");
$dbHandler->createUser($corentin, "coco");
$dbHandler->createUser($admin, "admin");

/*
// Coordonnées
$item7 = new Item("Adresse", '', $categorie2);
$item8 = new Item("Code postal", '', $categorie2);
$item9 = new Item("Ville", '', $categorie2);
$item10 = new Item("Pays", '', $categorie2);
$item11 = new Item("Email", '', $categorie2);
$item12 = new Item("Téléphone", '', $categorie2);

// Famille
$item13 = new Item("Situation", '', $categorie3); //select ('Seul(e)', 'En couple', 'Marié(e)', 'Divorcé(e)')
$item14 = new Item("Enfants à charge", '', $categorie3); //duplicate ?

// Etudes
$item15 = new Item("Niveau d'étude", '', $categorie4); //select ('BAC', 'BAC+1', '...')
$item16 = new Item("Dernier diplôme", '', $categorie4);

// Professionnel
$item17 = new Item("Statut", '',$categorie5); //select ('Etudiant', 'Salarié (privée)', 'Fonctionnaire')
$item18 = new Item("Lieu actuel d'études", '',$categorie5);
$item19 = new Item("Entreprise", '',$categorie5); //select ('BAC', 'BAC+1', '...')
*/

//$dbHandler->createCategory($categorie1, )

//$ownersDB->create($mathieu, $artwork1);
//$ownersDB->create($corentin, $artwork2);
?>

