In order to set up a development environment:

This project requires Eclipse PDT 3.1.x, an integrated LAMP server such as MAMP or XAMPP supporting PHP 5.3.3 (or higher), MySQL 5.5.25 (or higher).

 - Go to http://wiki.eclipse.org/PDT/Installation_3.1.x and follow these steps to set up Eclipse:
     - Download Eclipse IDE for Java EE developers 4.2 and install it (instead of Eclipse Classic).
     - Install PDT 3.1.1 nightly.
     - In the Eclipse preferences for General, Workspace, set Text file encoding to UTF-8.
     - Increase the Eclipse heap size to 128M and 1024M.
     - Follow http://www.vogella.com/articles/EGit/article.html to install EGit.

 - For Mac OS X, download MAMP 2.1.1 from http://www.mamp.info/en/downloads/index.html or a later version and install it.

 - For Windows, download XAMPP 1.8.0 from http://www.apachefriends.org/en/xampp.html or a later version and install it.
     - In the preferences set the Apache Port to 80, PHP Version to 5.4.4.

Running iTradeTunes:

See readme.txt for detailed configuration instructions of iTradeTunes for development, test and production environments.

Creating message files for modules in Poedit:

 - Set your Poedit preference as follows:
     - Personalize tab: Under Your name, enter your name; under Your email address, enter your itradetunes.com email address.
     - Parsers tab, PHP: Under List of extensions, enter: "*.php;*.phtml;" under Parser command, enter:
         "xgettext --language=PHP --force-po -o %o %C %K %F". The --language=PHP option prevents awarning message when parsing PHTML files.

 - A language catalog is created in the language directory of each module and then under the subdirectory for the language, such as en. Enter the following settings:
     - Project info tab: Project name and version: iTradeTunes V1.0.0.0
     - Project info tab: Team's email address: support@itradetunes.com
     - Project info tab: Language: English
     - Project info tab: Charset: UTF-8
     - Project info tab: Source code charset: UTF-8
     - Paths tab: Base path: /Users/pwansch/git/iTradeTunes/iTradeTunes/module/<module>
     - Keywords tab: Add the keyword translate as the Zend Framework translate view helper function is called translate()

 - Run Catalog > Update from sources to parse the source code files for strings.