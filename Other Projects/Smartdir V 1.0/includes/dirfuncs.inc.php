<?php
/**
* SmartDir Functions file
* This file contains the basic functions needed to work with directories
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 0.1
* @since 0.1
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
*/
// include the main configuration file of the application
// require_once('corefuncs.inc.php');

/**
* Directory-related Functions
*/
// Get the current working directory
function getCurrentDir()
{
$dir = 'getcwd';
return $dir;
}

// Get the parent directory above the current directory
function getParentDir($dir)
{
$parentDir = dirname($dir);
return $parentDir;
}

function in_array_r($needle, $haystack) { 
    $found = false; 
    foreach ($haystack as $item) { 
    if ($item === $needle) {  
            $found = true;  
            break;  
        } elseif (is_array($item)) { 
            $found = in_array_r($needle, $item);  
            if($found) {  
                break;  
            }  
        }     
    } 
    return $found; 
}

// function that gets the minetype of the file given
function getMimeType($filename)
{
// open the file to get its mime type
$finfo = finfo_open();
// get the mimetype of the file
$fileMimeType = finfo_file($finfo, $filename, FILEINFO_MIME_TYPE);
// close the file
finfo_close($finfo);
// return the type
return $fileMimeType;
}
// pass through the obtained file to the browser using the fpassthru command
function openFile($filename, $mimeType)
{
// strip the filename off its location
$filenameStripped = basename($filename);
// open the file in a binary mode
$fp = fopen($filename, 'rb');
// send the right headers
header("Content-Type: $mimeType");
/** make an exception for a plain text file and send the headers as an attachment
* DEPRICATED AS NOT NEEDED
* Lleft out only for clarity and for an example of usage if you need to exclude other filetypes from being opened directly within the browser
if ($mimeType == 'text/plain')
{
header("Content-Disposition: attachment; filename= $filenameStripped");
header("Content-Length: " . filesize($filename));
}
else
{
*/
header("Content-Disposition: inline; filename= $filenameStripped");
header("Content-Length: " . filesize($filename));
// }
// dump the file to the browser
return fpassthru($fp);
}

// List all files, folders and subdirectories
// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function getFileList($dir, $recurse=false)
  {
// array to hold return value
$retval = array();
// add trailing slash if missing
if(substr($dir, -1) != "/") $dir .= "/";
// open pointer to directory and read list of files
$d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
while(false !== ($entry = $d->read()))
{
// skip hidden files
if($entry[0] == ".")
continue;
if(is_dir("$dir$entry"))
{
$retval[] = array(
"name" => "$dir$entry/",
"type" => filetype("$dir$entry"),
"size" => 0,
"lastmod" => filemtime("$dir$entry"),
);
        if($recurse && is_readable("$dir$entry/"))
{
          $retval = array_merge($retval, getFileList("$dir$entry/", true));
        }
}
elseif(is_readable("$dir$entry"))
{
        $retval[] = array(
          "name" => "$dir$entry",
          "type" => filetype("$dir$entry"),
          "size" => filesize("$dir$entry"),
          "lastmod" => filemtime("$dir$entry"),
);
      }
    }
    $d->close();
return $retval;
}

/**
* functions that read and write files
*/

// function that writes a .htaccess file to the specified folder
function writeHtaccess($file, $data)
{
// check if the file you are trying to write to already exists
if (!file_exists($file))
{
// first, create a new .htaccess file
$fh = fopen($file, 'w') or die("can't open the file $file");
// write the data into the file
fwrite($fh, $data);
// close the file after the data is being written
fclose($fh);
}
else
{
// open the existing file and append the data into it
$fh = fopen($file, 'a') or die("can't open the existing file $file");
// write the data into the file
fwrite($fh, $data);
// close the file
fclose($fh);
}
}

// function that reads data from a .htaccess file
function readHtaccess($file)
{
// check if the file you are trying to read data from already exists and that it is writable
if (file_exists($file) && is_writable($file))
{
// first, open the .htaccess file for reading
$fh = fopen($file, 'r') or die("can't open the file $file for reading.");
// initialise a pointer to 0
$i = 0;
// read the data off the file using the fgets command
while (!feof($fh))
{
$line = fgets($fh);
// increment the pointer as many positions as lines are available in the read file
$i++;
// insert every line of the file into an array
$theData[$i] = $line;
}
// close the file after the data is being read
fclose($fh);
}
// return an array containing all lines from the file
return $theData;
}

function readUser($file)
{
// check if the file you are trying to read data from already exists and that it is writable
if (file_exists($file) && is_writable($file))
{
// first, open the .usrs file for reading
$fh = fopen($file, 'r') or die("can't open the file $file for reading.");
// initialise a pointer to 0
$i = 0;
// read the data off the file using the fgets command
while (!feof($fh))
{
$line = fgets($fh);
// explode the line into an array
$newLine = explode('	', $line);
// increment the pointer as many positions as lines are available in the read file
$i++;
// insert every line of the file into an array
$theUser[$i] = $newLine;
}
// close the file after the data is being read
fclose($fh);
}
// return an array containing all lines from the file
return $theUser;
}

// function that reads an .passwd file
function readHtpasswd($file)
{
// check if the file you are trying to read data from already exists and that it is writable
if (file_exists($file) && is_writable($file))
{
// first, open the .usrs file for reading
$fh = fopen($file, 'r') or die("can't open the file $file for reading.");
// initialise a pointer to 0
$i = 0;
// read the data off the file using the fgets command
while (!feof($fh))
{
$line = fgets($fh);
// explode the line into an array
$newLine = explode(':', $line);
// increment the pointer as many positions as lines are available in the read file
$i++;
// insert every line of the file into an array
$thePasswd[$i] = $newLine;
}
// close the file after the data is being read
fclose($fh);
}
// return an array containing all lines from the file
return $thePasswd;
}
// function that generates a password for a .htpasswd file
function generateHtaccessPassword($password)
{
// Encrypt password
$encryptedPassword = crypt(trim($password),base64_encode(CRYPT_STD_DES));
// return the encrypted password
return $encryptedPassword;
}

// function that generates the contents of the .htaccess file
function generateHtaccessFile($file, $data)
{
// write the following data to a new file
$data = sprintf('AuthType Basic
AuthName "Protected Area"
Require valid-user
');
	// write the file
writeHtaccess($file, $data);
// return true on success and false on failure
return true;
}

/**
     * Authenticate a user against a password file generated by Apache's httpasswd
     * using PHP rather than Apache itself.
     *
     * @param string $user The submitted user name
     * @param string $pass The submitted password
     * @param string $pass_file='.htpasswd' The system path to the htpasswd file
     * @param string $crypt_type='DES' The crypt type used to create the htpasswd file
     * @return bool
     */
    function http_authenticate($user,$pass,$pass_file='.htpasswd',$crypt_type='DES'){
        // the stuff below is just an example useage that restricts
        // user names and passwords to only alpha-numeric characters.
        if(!ctype_alnum($user)){
            // invalid user name
            return FALSE;
        }
        
        if(!ctype_alnum($pass)){
            // invalid password
            return FALSE;
        }
        
        // get the information from the htpasswd file
        if(file_exists($pass_file) && is_readable($pass_file)){
            // the password file exists, open it
            if($fp=fopen($pass_file,'r')){
                while($line=fgets($fp)){
                    // for each line in the file remove line endings
                    $line=preg_replace('`[\r\n]$`','',$line);
                    list($fuser,$fpass)=explode(':',$line);
                    if($fuser==$user){
                        // the submitted user name matches this line
                        // in the file
                        switch($crypt_type){
                            case 'DES':
                                // the salt is the first 2
                                // characters for DES encryption
                                $salt=substr($fpass,0,2);
                                
                                // use the salt to encode the
                                // submitted password
                                $test_pw=crypt($pass,$salt);
                                break;
                            case 'PLAIN':
                                $test_pw=$pass;
                                break;
                            case 'SHA':
                            case 'MD5':
                            default:
                                // unsupported crypt type
                                fclose($fp);
                                return FALSE;
                        }
                        if($test_pw == $fpass){
                            // authentication success.
                            fclose($fp);
                            return TRUE;
                        }else{
                            return FALSE;
                        }
                    }
                }
                fclose($fp);
            }else{
                // could not open the password file
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
	
	