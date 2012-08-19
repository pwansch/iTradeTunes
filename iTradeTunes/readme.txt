This project requires Eclipse PDT 2.2.0 (or higher) installed. Follow these steps:

    * Download a package for your platform from the Eclipse PDT 2.2.0 All In Ones / Eclipse PHP Package section at http://www.eclipse.org/pdt/downloads/ such as eclipse-php-helios-macosx-cocoa-x86_64.tar.gz.
    * Install the Subversion Team Provider feature by following the instructions at http://www.polarion.com/products/svn/subversive/download.php for the Helios release. The next time Eclipse starts, on the first call
      to any Subversive functionality the Connector Discovery feature will detect that there are no connectors installed and will launch a dialog which displays the SVN Connectors you need and enables download and 
      installation. Install the SVNKit 1.3.2. If a perspective is in error, reset it. 

This project also requires MySQL 5.0.41 (minimum), 5.1.32 (recommended) or higher, Apache 2.0.59 (minimum), 2.2.3 (recommended) or higher, and PHP 5.2.4 (minimum), 5.2.9 (recommended) or higher. PHP requires the 
GD extension compiled with TrueType or Freetype support to display the CAPTCHA images.

For Windows, we recommend to use Zend Server CE. Download Zend Server CE (PHP 5.2) from http://www.zend.com/en/products/server-ce/downloads and choose the following options:

    * Choose "Custom installation".
    * Select "phpMyAdmin".
    * Select "MySQL Server".
    * Use the default port 80. 

After you have installed Zend Server go to the Zend Server Community Edition Dashboard and click on Open phpMyAdmin at http://localhost/phpMyAdmin. Log in as root but don't enter a password. 
On the phpMyAdmin home page click on 'Change password' and change the root password to root.

For Mac OS X, we recommend MAMP. The most current version at the time of writing is 1.9.4 and can be downloaded from http://www.mamp.info/.

To edit gettext catalogs (.po files) and to generate .mo files for the message localization to support languages, install Poedit from http://sourceforge.net/projects/poedit/. 
The most current version at the time of writing is 1.4.6.1.

For development we recommend Firefox 3.6.12 (or higher) with Firebug 1.5.4 (or higher). Dust-Me Selectors is a Firefox add-on that helps finds unused CSS selectors. 
It can be downloaded from http://www.sitepoint.com/dustmeselectors/.

Eclipse Settings

    * Ensure that General, Workspace, Text file encoding is set to UTF-8 in the Eclipse preferences.
    * Include the Zend Framework library folder in the PHP Include Path project properties.
    * If you use Zend Server CE, set the path to both the PHP executable and the PHP ini file in the PHP, PHP Executables settings and verify that Zend Debugger is selected as the PHP debugger.         
        
See application/bootstrap.php for detailed configuration instructions of GoGoVerde for development, test and production environments.