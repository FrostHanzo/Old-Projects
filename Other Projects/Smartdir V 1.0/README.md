ReadME File for
Smartdir V 1.0

Author: Georgi Zhivankin for ilyan.com
Version: 1.0
Release Date: 29 February 2012

* * *

Introduction

This script provides an ability to restrict files and folders on per user basis using an easy-to-use web interface.

* * *

System Requirements

The script requires the following components to be installed and running on your system in order to function correctly:

1. Apache Webserver V 2.2 or greater. Tested on Apache V 2.2.21
2. PHP Version 5, minimum 5.3.5. Tested on PHP V 5.3.8.
3. Fileinfo (finfo) Extention for PHP V 1.0.5 or newer. Tested on Fileinfo 1.0.5 dev.
4. MySQL V 5 database engine or newer. Tested on 5.5.16.

* * *

Installation and Configuration

1. Unpack the archive into your web root directory on your web server.
2. Go and by using your favourite text editor edit the configuration file (config.php) located at the main directory of the script.
2.1. Change the settings in the GENERAL SETTINGS section according to your requirements.
2.2. Change the paths within the directories section to match the paths of your individual server's configuration.
3. Save and close the file.
4. Take the db_schema.sql file off the /db folder and either import its contents into your database software (if you can create new databases on your server) or copy everything after the line which says:
'USE smartdir';
and import it into your own database. The prefix of the smartdir tables is smartdir_ and cannot be changed at the moment as the script is not coded to take other prefixes into account and would break if you modify the default table names.
4. Locate the mysql.inc.php file within the /includes folder where the database connection settings are defined and change the following parameters to match your server's configuration:
function Mysql()
{
$this->dbUser = 'your_db_user';
$this->dbPass = 'your_db_pass';
$this->dbHost = 'your_db_host';
$this->dbName = 'your_db_name';
}
5. Close and save the file.
6. Go to the login page at http://<yourdomain.com>/<directory_where_the_script_is_placed> and type in the default username and password to login into your administrator interface:
Username: administrator
Password: administrator
7. The default .htaccess file within the main directory of the script disables the default Apache directory indexes and sets the local PHP timezone, but if you need to change this behaviour or you would like to use your own .htaccess file, don't hesitate to remove the default one or replace it with a custom one. A copy of the default .htaccess file can be found within the includes folder.
8. That's it, you are ready to start using your new directory access control script.

* * *

Questions or Comments

If you want to ask me questions, get more information about the script, make suggestions for future improvements of the script, feel free to contact me using the contact form on my personal website at:
http://zhivankin.com/contact/

* * *

Copyright Notices

This script and all of its related contents is copyright 2010, Georgi Zhivankin. All rights reserved. The code or any part of it, shall not be used for any commercial or non-commercial use without an explicit written permission by the author.

Thank you and enjoy!