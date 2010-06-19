<?php
define("IN_MODULE",true);
require_once('../includes/FileScopeReplacer.php');
require_once('../includes/config.inc.php');
require_once('../includes/functions.inc.php');

$oldcop = 'luxCOP = "' . $luxCOP . '"';
$newcop = 'luxCOP = "' . trim(strip_tags($_GET['newcop'])) . '"';

if( empty($newcop) || $newcop == "" || $newcop == " ") {
	die ($newcop . "      -Incorrect params. Check them.");
}

$params = array();

//--------------- configuration --------------

// directory where files will be searched to replace
$params['dir'] = '../includes/';

// set to 1 if you want to proceed also nested directories
$params['include_nested'] = 0;

// this is string of what you are looking for
$params['search_what'] = $oldcop;

$params['replace_to'] = $newcop;

// setting for filtering, set '' if no filtering,
// otherwise thi is regexp expression like: '/your_regexp/flags', 
// see http://www.php.net/manual/en/pcre.pattern.syntax.php
$params['file_name_match'] = '/^config.inc.php/';  // <-- this mean beginning from 'test'


//--------------- end configuration --------------

$replacer = new FileScopeReplacer( $params );
$replacer->doWork();

echo "LuxCOP was changed successfully from $oldcop to $newcop.<br><br><a href='index.php'>Back</a>";

?>