<?php

require_once("src/persondbstub.php");
require_once("src/testpersondb.php");

echo "Testing class PersonDBStub... ";
try {
    TestPersonDB::test(new PersonDBStub());
} catch (Exception $e) {
    echo "\nTests failed: ".$e;
    exit(1);
}
echo "OK";

?>

