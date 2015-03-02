<?php

/**
 * A class representing letters in the Latin alphabet.
 */
class Character {

    /** The letter. */
    protected $char;

    /** The rank of the letter in the alphabet. */
    protected $rank;

    /**
     * Builds a new instance.
     * @param $char A letter
     */
    public function __construct ($char) {
        $this->char = $char;
		$this->rank = ord($char)-ord('a')+1;
    }

    /**
     * Returns the letter represented by this character.
     * @returns A character
     */
    public function getLetter () {
        return $this->char;
    }

    /**
     * Returns the rank of this letter in the alphabet (1 for 'a', 2 for 'b', etc.).
     * @returns An integer
     */
    public function getRank () {
        return $this->rank;
    }

}

?>
