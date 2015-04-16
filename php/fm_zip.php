<?php

// Set level of error handling
ini_set('log_errors', 0);
ini_set('display_errors', 0);

$global_user = 'php';
$global_pass = 'php';
$global_host = 'http://your_webdirect_server/'; // enter the address of the server hosting webdirect
$global_db = 'WebDirect_Zip'; // enter the name of the file

//include the FileMaker PHP API
require_once ('FileMaker.php');
$fm = new FileMaker();
$fm->setProperty('database', $global_db);
$fm->setProperty('hostspec', $global_host);
$fm->setProperty('username', $global_user);
$fm->setProperty('password', $global_pass);

$find = $fm->newFindCommand('web_files');

//Specify the find criteria.
$find->addFindCriterion('ID_Contacts', $_REQUEST['id']);

// enable if sort is needed
// $find->addSortRule('Status', 1, FILEMAKER_SORT_DESCEND);
// $find->addSortRule('Order', 2, FILEMAKER_SORT_DESCEND);

// support up to the range set for found set
$find->setRange(0, 1000);

//Perform the find
$results = $find->execute();

//Check for errors
if (!FileMaker::isError($results)) {
    //No errors, return first matching result
    $fm_records = $results->getRecords();
    $foundtix = count($fm_records);
} else {
    //There was an error, return null (i.e. no matches found)
    $err = 'Unexpected Error: ' . $results->getMessage();
    exit($err);
}


// create the zip archive 
$zip = new ZipArchive();
$zipname = 'records_' . date('U');
$filename = '/tmp/' . $zipname . '.zip';

if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
    exit("cannot open <$filename>\n");
}

foreach ($fm_records as $value) {

    $the_file = base64_decode($value->getField('File_b64'));
    $the_file_name = $value->getField('File_name');
    $zip->addFromString($the_file_name, $the_file);
}

$zip->close();

$file_type = strtolower(array_pop(explode('.', $filename)));
$file_name = array_pop(explode('/', $filename));

header('Pragma: public');
header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 4, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0");
header("Content-Description: File Transfer");
header("Content-Transfer-Encoding: binary");
header("X-Download-Options: noopen");
header("X-Content-Type-Options: nosniff");
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . "\"$file_name\"");

// put the content in the file
fpassthru(fopen($filename, 'r'));

// remove the file once read
unlink($filename);

// stop processing the page
exit("");
