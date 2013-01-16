<?php 
    require_once('facebook-php-sdk/facebook.php');
    require_once('Database.class.php');

   session_start();

    // Retrieve SESSION variables for db and facebook
    $db = $_SESSION['db'];
    $facebook = $_SESSION['facebook'];
    
    // Create New Album and update bread_history DB
    $album = $facebook->api('/me/albums','POST',array('name'=>'Cheeseburger Daily Bread '.date('F j, Y - H:i'), 'description'=>'Daily Bread'));
    $db->Query("INSERT INTO bread_history (album_ID, date) VALUES (".$album['id'].",'".date('Y-m-d')."')");
    
    // Add Photos and update daily_bread DB
    $result = $db->GetAlbumPhotos(3);

    foreach($result as $row)
    {              
      // Upload photo to album
      $photo = $facebook->api('/'.$album['id'].'/photos','POST',array('url'=>$row['url'],'name'=>$row['comment'],'no_story'=>'1'));

      // update DB
      $db->Query("UPDATE daily_bread SET album_ID='".$album['id']."' WHERE uid=".$row['uid']);
    } 

    // Create Post for Front Page
    $postInfo = $facebook->api('/'.$album['id'].'/?fields=link,name','GET');
    $post = $facebook->api('/me/feed','POST',array('link'=>$postInfo['link'],'description'=>'Daily Bread'));

    //close mysql connection
    
?>
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

    <div class="container-narrow">

        <h1>Johnny Cheeseburger's Daily Bread Factory</h1>

        <hr>

        <div class="jumbotron">
          <h2>Fresh Bread</h2>
          <br>
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success</strong>You just posted some Bread!
          </div>
        </div>

        <div class="footer">
          <p>&copy; Reustonium 2012</p>
        </div>

    </div> <!-- /container -->

    <!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>

  </body>
</html>