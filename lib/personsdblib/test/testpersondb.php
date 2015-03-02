<?php

require_once("config.php");
require_once("src/personsdb.php");
require_once("persons/testpersondb.php");

echo "Testing PersonsDB... ";

// Creating PDO instance and database
$pdo=new PDO("mysql:host=".HOST.";port=".PORT.";dbname=".DB,USER,PWD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_PERSISTENT => true));
$pdb=new PersonsDB($pdo,"testpersons");

// Creating tables
$pdb->deleteTables();
$pdb->createTables();

TestPersonDB::test($pdb);

/*
// Creating two test persons
$pmd=new Person("Marie","Dupont","marie.dupont@mail.fr");
$ptest=new Person("Prenomtest","Nomtest","adresse.test@test.fr");
$pdb->create($pmd,"mot de passe");
$pdb->create($ptest,"mot de passe de test");

// Checking retrieval of all persons
$persons=$pdb->retrieveAll();
assert(count($persons)==2);

// Checking password verification and update
assert($pdb->isValid("marie.dupont@mail.fr","mot de passe"));
assert(!$pdb->isValid("marie.dupont@mail.fr",""));
assert(!$pdb->isValid("marie.dupont@mail.fr","mot de passe de test"));
assert($pdb->isValid("adresse.test@test.fr","mot de passe de test"));
assert(!$pdb->isValid("adresse.test@test.fr",""));
assert(!$pdb->isValid("adresse.test@test.fr","mot de passe"));
$pdb->updatePassword("marie.dupont@mail.fr","mot de passe de test");
assert(!$pdb->isValid("marie.dupont@mail.fr","mot de passe"));
assert(!$pdb->isValid("marie.dupont@mail.fr",""));
assert($pdb->isValid("marie.dupont@mail.fr","mot de passe de test"));

// Checking retrieval of one person
$md=$pdb->retrieve("marie.dupont@mail.fr");
assert(strcmp($md->getLastName(),"Dupont")==0);
assert(strcmp($md->getFirstName(),"Marie")==0);
assert(strcmp($md->getEmail(),"marie.dupont@mail.fr")==0);

// Cleaning and verification of cleaning
foreach ($persons as $person) {
  $pdb->delete($person->getEmail());
}
assert(count($pdb->retrieveAll())==0);
$pdb->deleteTables();
*/

// Closing PDO connection
$pdo=NULL;

echo "OK.\n";

?>
