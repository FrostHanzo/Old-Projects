<?php
function getCurrentDir()
{
$dir = 'getcwd';
return $dir;
}
function getParentDir($dir)
{
$parentDir = dirname($dir);
return $parentDir;
}
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
"lastmod" => filemtime("$dir$entry")
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
          "lastmod" => filemtime("$dir$entry")
        );
      }
    }
    $d->close();
return $retval;
}
getFileList("C:/xampp/htdocs/");