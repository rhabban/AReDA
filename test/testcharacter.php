<?php

require_once("model/Character.php");

echo "Testing Character... ".PHP_EOL;

/**
 * Tests the Character class for a given letter. An assertion error is
 * throws if the test fails.
 * @param $letter A letter
 * @param $rank The correct rank of $letter in the alphabet
 */
function testLetter ($letter, $rank) {
    $character=new Character ($letter);
    assert($character->getLetter()===$letter);
    assert($character->getRank()===$rank);
}

testLetter('a',1);
testLetter('b',2);
testLetter('m',13);
testLetter('z',26);

echo "Testing Character class: done.".PHP_EOL;

?>
