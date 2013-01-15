<html>
<head>
	<title>Test File for Database.class.php</title>
</head>
<body>
<h1>Number of Unused Images in the DB</h1>
<?php
	require_once('../Database.class.php');

	$db = new Database();
	$test = $db->CountNewPhotos();
	echo $test;
?>

</body>
</html>
