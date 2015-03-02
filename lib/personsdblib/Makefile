tests: testpersondb

init:
	@if [ ! -d doc ] ; then mkdir doc ; fi

clear: clean
	rm -rf doc

documentation: init
	@doxygen

clean:
	@find . -name "*~" -exec rm -i {} \;

# Auxiliary targets

testpersondb: src/personsdb.php test/testpersondb.php
	@php test/testpersondb.php
