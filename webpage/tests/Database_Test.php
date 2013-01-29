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
<br>
<?php
	echo ("test yes this is a dup: ");
	$isdup = $db->IsUrlDup('test');
	echo $isdup;

	echo ('<br>');
	echo ("dog NO this is NOT a dup: ");
	$isdup = $db->IsUrlDup('dog');
	echo $isdup;
?>
<hr>
<h1>new user test</h1>
<?php
	$newU = $db->isNewUser(100);
	echo $newU;
?>

<h1>addStat Test</h1>
<?php
	$db->AddStats('100','photo_likes');
	$db->AddStats('101','photo_comments');
	$db->AddStats('101','album_likes');
?>

<h1>Get AlbumID Test</h1>
<?php
	$aid = $db->GetAlbumIDs();
	echo $aid;
	foreach($aid as $a){
		echo ($a.'<br>');
	}
?>
</body>
</html>
