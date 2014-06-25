<?php
/**
*
* writeFile.php
* This file contains the functions needed to write .htaccess files to the server
*/
// include the configuration script
require_once('config.php');
// write the following data to a new file
$file = '.htaccess';
$path = "AuthUserFile ".dir.ds.".htpasswd";
$data = sprintf('%s
AuthType Basic
AuthName "Protected Area"
Require valid-user
Options-Indexes
', $path);

echo $data;
// write the file
// echo "The data I want to write is: " .$data . "\n The filename is: " .$file . "\n Directory: " . dir . ds . "\n";
writeHtaccess($file, $data);