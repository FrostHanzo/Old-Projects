ReadME File for
Commercial Website Demo for 2nd year University Electronic Commerce Module

Author: Georgi Zhivankin
Version: 1.0
Release Date: 30 April 2012

* * *

Introduction

This script is a demo made in PHP and MySQL for a university module called 'Electronic Commerce'. It simulates a commerce site of a company that enables its users to browse and shop for different products that the company produces.

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
2.1. Server and Directory settings. Change the following settings under this section:
2.1.1. The default directory where the script resides is defined as a constant: define('SERVER_DOC_ROOT', '/').
2.2. In the database settings section, define the following settings:
2.2.1. The host of the DB server: define('DB_HOST', 'db_server_host').
2.2.2. The name of the database that holds the table schema: define('DB_NAME', 'dbname').
2.2.3. The database username: define('DB_USER', 'dbuser').
2.2.4. The database password: define('DB_PASS', 'dbpass').
2.2.5. The database prefix (default is in3008_): define('DB_PREFIX', 'in3008_').
2.3. You could change the default password salt for hashing the usernames and passwords by changing the value of: $password_Salt.
2.4. Under the Upload Picture Settungs section, you could change:
2.4.1. The variable for the maximum permitted picture size: define('MAX_FILE_SIZE', 2097152).
3. Save and close the file.
4. Take either the in3008_db_schema_with_sample_data.sql or the in3008_db_schema_without_data.sql file off the /db directory and either import its contents into your database software (if you can create new databases on your server) or create a new database that has the same name as the name set in the config file earlier and copy everything from the .SQL file and paste it into your own database. The default prefix of the tables is in3008_.
5. Go to the login page at http://<yourdomain.com>/<directory_where_the_script_is_placed> and type in the default username and password to login into your administrator interface:
Username: administrator
Password: administrator
6. That's it, you are ready to start using the demo application.

* * *

Questions or Comments

If you want to ask me questions, get more information about the script, make suggestions for future improvements of the script, feel free to contact me using the contact form on my personal website at:
http://zhivankin.com/contact/

* * *

Copyright Notices

This script and all of its related contents is copyright 2012, Georgi Zhivankin and City University London. All rights reserved. The code or any part of it, shall not be used for any commercial or non-commercial use without an explicit written permission by the author.

Thank you and enjoy!