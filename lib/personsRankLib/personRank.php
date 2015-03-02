<?php

require_once("personslib/src/person.php");

class PersonRank extends Person{
    
    protected $rank;
    
    public function __construct ($firstName, $lastName, $email, $rank) {
        parent::__construct($firstName, $lastName, $email);
        $this->rank = $rank;
    }
    
    function getRank() {
        return $this->rank;
    }

    function setRank($rank) {
        $this->rank = $rank;
    }

}
