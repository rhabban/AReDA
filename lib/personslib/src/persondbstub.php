<?php

require_once("person.php");
require_once("ipersondb.php");

/**
 * A stub for interface IPersonDB, which does provide RAM persistency but no long-term persistency.
 * @author Bruno Zanuttini, Universit&eacute; de Caen Basse-Normandie, France
 * @since February, 2013
 */
class PersonDBStub implements IPersonDB {

    /** A map password => Person storing the persons. */
    protected $persons;

    /**
     * Builds a new, empty list of persons.
     */
    public function __construct () {
        $this->persons=array();
    }

    public function create (Person $person, $password) {
        if ($this->exists($person->getEmail())) {
            throw new Exception("Cannot add ".$person.": email already exists");
        }
        $this->persons[$password]=$person;
    }

    public function retrieveAll () {
        return array_values($this->persons);
    }

    public function retrieveAllEmails () {
        $res=array ();
        foreach ($this->persons as $person) {
            $res[]=$person->getEmail();
        }
        return $res;
    }

    public function retrieve ($email) {
        foreach ($this->persons as $person) {
            if ($person->getEmail()===$email) {
                return $person;
            }
        }
        throw new Exception("No person with email ".$email);
    }

    public function isValid ($email, $password) {
        return isset($this->persons[$password]) && $this->persons[$password]->getEmail()===$email;
    }

    public function exists ($email) {
        foreach ($this->persons as $person) {
            if ($person->getEmail()===$email) {
                return true;
            }
        }
        return false;
    }

    public function update ($email, Person $person) {
        $password=null;
        foreach ($this->persons as $pwd => $p) {
            if ($p->getEmail()===$email) {
                $password=$pwd;
                break;
            }
        }
        if ($password===null) {
            throw new Exception("No person with email ".$email);
        }
        $this->persons[$password]=$person;
    }

    public function updatePassword ($email, $password) {
        $oldPassword=null;
        foreach ($this->persons as $pwd => $p) {
            if ($p->getEmail()===$email) {
                $oldPassword=$pwd;
                break;
            }
        }
        if ($oldPassword===null) {
            throw new Exception("No person with email ".$email);
        }
        $person=$this->persons[$oldPassword];
        unset($this->persons[$oldPassword]);
        $this->persons[$password]=$person;
    }

    public function delete ($email) {
        $oldPassword=null;
        foreach ($this->persons as $password => $person) {
            if ($person->getEmail()===$email) {
                $oldPassword=$password;
                break;
            }
        }
        if ($oldPassword===null) {
            throw new Exception("No person with email ".$email);
        }
        unset($this->persons[$oldPassword]);
    }

}

