In order to run unit tests:

 - Add the path to the MAMP binaries to your .profile:
     export PATH=/Applications/MAMP/bin/php/php5.4.4/bin:$PATH
   
 - Turn on pear auto discover:
     pear config-set auto_discover 1
     
 - Install PHPUnit:
     sudo pear install pear.phpunit.de/PHPUnit

 - Change to the tests directory and run:
     phpunit    