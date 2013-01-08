<? 
  require_once('facebook-php-sdk/facebook.php');

  // facebook Connection and Authentication
  $config = array();
  $config[‘appId’] = '238552796278527';
  $config[‘secret’] = '83f3fa6948e194c5da23ca7297df6d02';

  $facebook = new Facebook($config);
 
  // Get access token
  //$pages = $facebook->api('/me/accounts','GET');
  $user = $facebook->getUser();


  // mySQL Connection
  $con = mysql_connect("localhost:3306","aplinein_jc","pizzacake");
	if (!$con)
	{
  		die('Could not connect: ' . mysql_error());
  	}

  mysql_select_db("aplinein_dailybread", $con);


?>

<html>
	<head></head>
	<body>
		<?

			echo $user;
			//echo '<br>'.$pages[0];

			// Create New Album
			//$albumDate = date('F j, Y - H:i');
			//$album = $facebook->api('/me/albums','POST',array('name'=>'Cheeseburger Daily Bread '.$albumDate, 'description'=>'Daily Bread'));

			// Grab SQL Data
			//$result = mysql_query("SELECT * FROM daily_bread WHERE album_ID IS NULL LIMIT 2");

			// while($row = mysql_fetch_array($result))
  	// 		{
  	// 			echo "<br />";
  	// 			echo 'comment: '.$row['comment']. ' uid: '.$row['uid'];
  				
  	// 			$photo = $facebook->api('/'.$album['id'].'/photos','POST',array('url'=>$row['url'],'name'=>$row['comment']));
  	// 		}


			// mysql_close($con);
		?>
	</body>
</html>