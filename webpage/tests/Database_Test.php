<html>
<head>
	<?php
		require_once('../Database.class.php');
	?>
	<title>Test File for Database.class.php</title>
</head>
<body>
<h1>Number of Unused Images in the DB</h1>
<?php
	$db = new Database();
	$test = $db->CountNewPhotos();
	echo $test;
?>

<hr>
Test for GetAlbumPhotos($numPhotos)
<br>
<?php
	$albumPhotos = $db->GetAlbumPhotos(4);
	foreach($albumPhotos as $ap){
		echo($ap['url']);
		echo('<br>');
		echo($ap['comment']);
		echo('<hr>');
	}
?>
</body>
</html>
