<?php

require_once("ipersondb.php");

/**
 * A class for running some basic tests on classes which implement interface IPersonDB.
 * @author Bruno Zanuttini, Universit&eacute; de Caen Basse-Normandie, France
 * @since February, 2013
 */
class TestPersonDB {

    /**
     * Runs a series of tests on an instance of a class which implements IPersonDB.
     * The instance is assumed to represent an empty database of persons when passed to
     * this method. If tests go well, the database is empty again when the method exits.
     * The method uses assertions to run tests.
     * @param $instance An instance of the class to be tested, representing an empty
     * database of persons
     * @throws Exception if an unexpected error occurs
     */
    public static function test (IPersonDB $instance) {
	$instance->create(new Person("Marie","Dupont","marie.dupont@mail.fr"),"eiram");
        $instance->create(new Person("Jean","Martin","jean.martin@mail.com"),"naej");
        $instance->create(new Person("Nicolas","Durand","nicolas.durand@mail.com"),"salocin");
        $instance->create(new Person("Emilie","Lefevre","emilie.lefevre@mail.fr"),"eilime");

        // Testing "R" methods
        $all=$instance->retrieveAll();
        assert(count($all)===4);
        $nicolasFound=false;
        foreach ($all as $person) {
            if ("nicolas.durand@mail.com"===$person->getEmail()) {
                $nicolasFound=true;
            }
        }
        assert($nicolasFound);
        $allEmails=$instance->retrieveAllEmails();
        assert(count($allEmails)===4);
	$nicolasFound=false;
        foreach ($allEmails as $email) {
            if ("nicolas.durand@mail.com"===$email) {
                $nicolasFound=true;
            }
        }
        assert($nicolasFound);
        assert($instance->exists("marie.dupont@mail.fr"));
        assert(!$instance->exists("jacques.durand@mail.com"));
        $marie=$instance->retrieve("marie.dupont@mail.fr");
        assert("Dupont"===$marie->getLastName());
        assert("Marie"===$marie->getFirstName());
        assert("marie.dupont@mail.fr"===$marie->getEmail());
        assert($instance->isValid("marie.dupont@mail.fr","eiram"));
        assert(!$instance->isValid("marie.dupont@mail.fr","naej"));
        assert(!$instance->isValid("marie.dupont@mail.fr",""));

        // Testing "U" methods
        $instance->update("jean.martin@mail.com",new Person("Jeannot","Martinet","jeannot.martinet@mail.com"));
        assert($instance->exists("jeannot.martinet@mail.com"));
        $jeannot=$instance->retrieve("jeannot.martinet@mail.com");
        assert("Martinet"===$jeannot->getLastName());
        assert("Jeannot"===$jeannot->getFirstName());
        assert("jeannot.martinet@mail.com"===$jeannot->getEmail());

        $instance->updatePassword("nicolas.durand@mail.com","new");
        assert(!$instance->isValid("nicolas.durand@mail.com","salocin"));
        assert($instance->isValid("nicolas.durand@mail.com","new"));
        assert(!$instance->isValid("marie.dupont@mail.fr","new"));

        // Testing "D" methods
        $instance->delete("nicolas.durand@mail.com");
        assert(count($instance->retrieveAll())===3);
        assert(count($instance->retrieveAllEmails())===3);
        assert(!$instance->exists("nicolas.durand@mail.com"));
        assert($instance->exists("emilie.lefevre@mail.fr"));

    }

}

