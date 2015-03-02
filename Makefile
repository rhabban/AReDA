init:
	@if [ ! -d doc ] ; then mkdir doc ; fi

clear: clean
	rm -rf doc/html

documentation: init
	@doxygen doc/Doxyfile

install: clear
	php config/install.php

clean:
	@find . -name "*~" -exec rm -i {} \;

tests:
	@php test/testcharacter.php

