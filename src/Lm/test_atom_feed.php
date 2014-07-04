<?php
//Test program to JSON decode the master.atom xml file feed

//declare a var that will store the content in master.atom
$xmlfeed = "";

//Get the content of the master.xml
$file = fopen(__DIR__ . '\master.atom.txt', 'r');
if( $file){
	$xmlfeed = fread($file, filesize('master.atom.txt'));
} else {
	echo "Unable to open master.atom";
	die;
}
fclose($file);

//create a SimpleXmlElement object to store the content in
$xml = simplexml_load_string($xmlfeed);

//check the update date of the file
$updated = $xml->updated;
printf("The update date is: %s \n", $updated);

//check and print every entry on screen
foreach($xml->entry as $entry){
	print_r($entry);
}


?>