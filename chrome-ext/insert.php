<?php
require_once('webpage/Database.class.php');

// Database Connection
$db = new Database();

// Check for Duplicate Entry
if($db->IsUrlDup($_POST[url])){
	echo$_POST[url];
	echo('<br>');
	echo('This image is already in the queue');
} else{
	// Convert Checkbox to int 0=off, 1=on
	if($_POST[priority]=='on'){
		$prioritize=1;
	} else {
		$prioritize = 0;
	}
	
	// Create query
	$query = "INSERT INTO daily_bread (url, comment, priority) VALUES ('$_POST[url]','$_POST[comment]','".$prioritize."')";
	
	// Execute Query
	$db->Query($query);
	
	// Create HTML
	echo('Successfully Added to the Queue!');
}

?>