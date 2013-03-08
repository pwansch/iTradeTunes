In order to run iTradeTunes:

 - For development, add the following line to your /etc/hosts file:
     127.0.0.1       itradetunes.localhost

 - Set up a virtual host for iTradeTunes, pointing the document root to the 'public' directory under the application root:
     - In your httpd.conf file, ensure that the following line is not commented out:
       # Virtual hosts
       Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf
     - In your httpd-vhosts.conf file add an entry for your iTradeTunes virtual host and an alias for your userimages directory:
       <VirtualHost *:80>
           ServerName itradetunes.localhost
           DocumentRoot "/Users/pwansch/git/iTradeTunes/iTradeTunes/public"
           <Directory "/Users/pwansch/git/iTradeTunes/iTradeTunes/public">
               DirectoryIndex index.php
               AllowOverride All
               Order allow,deny
               Allow from all
           </Directory>
           Alias /userimages "/Users/pwansch/git/iTradeTunes/iTradeTunes/userimages"
           <Directory "/Users/pwansch/git/iTradeTunes/iTradeTunes/userimages">
               DirectoryIndex index.php
               AllowOverride All
               Order allow,deny
               Allow from all
           </Directory>
       </VirtualHost>
 
 - Ensure that your Apache Web server loads the PHP and rewrite modules by verifying
   that the following lines are not commented out in httpd.conf:
     LoadModule php5_module /Applications/MAMP/bin/php/php5.4.4/modules/libphp5.so
     LoadModule rewrite_module modules/mod_rewrite.so

 - Rename config/autoload local.php.[development|test|production] to local.php and edit it to match your environment.
   For instance: 
     'dsn' => 'mysql:dbname=itradetunes_dev;host=localhost',
     'username' => 'root',
     'password' => 'root',

 - Change the default isolation level in your MySQL cnf file:
     [mysqld]
     transaction-isolation = READ-UNCOMMITTED

 - Edit and run createdb.sql, loaddb.sql and loadtestdb.sql (for development only) in the 'sql' directory
   to create the database for your environment.

 - Change the minimum length of strings returned in a full text search (default is 4 chars): 
     [mysqld]
     ft_min_word_len = 3

 - Make sure the correct default timezone is set in your PHP ini file like this:
       date.timezone = 'America/Los_Angeles'
   Valid timezones can be found at:
       http://www.php.net/manual/en/timezones.php

 - For development, make sure that all errors are displayed in your environment by using
   the following settings in your PHP ini file:
     error_reporting  =  E_ALL
     display_errors = On
     display_startup_errors = On
   For production, make sure that you use the following settings in your PHP ini file:
     error_reporting = E_ALL & ~E_NOTICE
     display_errors = Off
     display_startup_errors = Off
     log_errors = On
     error_log =

 - Verify that the resource limits are set to the following values in your PHP ini file:
     max_execution_time = 180
     max_input_time = 180
     memory_limit = 500M