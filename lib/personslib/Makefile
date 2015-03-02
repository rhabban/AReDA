tests:
	@php test/testpersondbstub.php

init:
	@if [ ! -d doc ] ; then mkdir doc ; fi

clear: clean
	rm -rf doc

documentation: init
	@doxygen

clean:
	@find . -name "*~" -exec rm -i {} \;

