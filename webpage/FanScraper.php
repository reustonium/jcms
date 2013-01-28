<?php
    require_once('facebook-php-sdk/facebook.php');
    require_once('Database.class.php');

   	session_start();

    // Retrieve SESSION variables for db and facebook
    $db = $_SESSION['db'];
    $facebook = $_SESSION['facebook'];

    /**
     * @TODO Run Once per Album in aplinein_dailybread.bread_history by album_ID
     */

    /**
     * @TODO For each album_ID parse for comments, likes, shares
     */

    /**
     * @TODO for each photo in Album parse for coments, likes, shares
     */
    $album_ID = '476923245697150';
    $album = $facebook->api($album_ID.'?fields=name,photos.fields(likes,name)','GET');
?>

<!DOCTYPE html>
<html lang="en">
  	<head>
	    <meta charset="utf-8">
	    <title>Johnny Cheeseburger Bakery</title>
	    <meta name="description" content="Content Management System for Johnny Cheeseburger's Facebook Fan Page.">
	    <meta name="author" content="Reustonium">

	    <!-- styles -->
	    <link href="css/bootstrap.css" rel="stylesheet">
	    <link href="css/superhero.css" rel="stylesheet">
	    <link href="css/narrow.css" rel="stylesheet">

	    <!-- javascript -->
	    <script src="js/jquery-1.8.3.min.js"></script>
  	</head>
  	<body>
  		<?php 
  			echo $album['name']; 
  			echo ('<hr>');

  			foreach($album['photos']['data'] as $photo){
  				echo $photo['name'];
  				echo ('<ul>');

  				foreach($photo['likes']['data'] as $likes){
  					echo ('<li>'.$likes['name']);	
  					$db->AddPhotoLike($likes['id']);			
  				}
  				echo ('</ul>');
  			}

  		?>
  


  	<!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>

  	</body>
</html>