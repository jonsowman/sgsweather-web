<?php
define("IN_MODULE",true);
require_once('../includes/FileScopeReplacer.php');
require_once('../includes/config.inc.php');
require_once('../includes/functions.inc.php');

$oldcorr = 'ldr_cf = ' . $ldr_cf;
$newcorr = 'ldr_cf = ' . trim(strip_tags($_GET['newcorr']));

if( empty($newcorr) || $newcorr == "" || $newcorr == " ") {
	die ($newcorr . "      -Incorrect params. Check them.");
}

$params = array();

//--------------- configuration --------------

// directory where files will be searched to replace
$params['dir'] = '../includes/';

// set to 1 if you want to proceed also nested directories
$params['include_nested'] = 0;

// this is string of what you are looking for
$params['search_what'] = $oldcorr;

$params['replace_to'] = $newcorr;

// setting for filtering, set '' if no filtering,
// otherwise thi is regexp expression like: '/your_regexp/flags', 
// see http://www.php.net/manual/en/pcre.pattern.syntax.php
$params['file_name_match'] = '/^config.inc.php/';  // <-- this mean beginning from 'test'


//--------------- end configuration --------------

$replacer = new FileScopeReplacer( $params );
$replacer->doWork();

echo "LDRCORR was changed successfully.<br><br><a href='index.php'>Back</a>";

?>