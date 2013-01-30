<?php
    /**
     * Update Fan Statistics Database
     * 
     * @package Default
     * 
     * @author Reustonium 
     */

    require_once('facebook-php-sdk/facebook.php');
    require_once('Database.class.php');

   	session_start();

    // Retrieve SESSION variables for db and facebook
    $db = $_SESSION['db'];
    $facebook = $_SESSION['facebook'];

    /**
     * Clear Stats Table
     */
    $db->ClearTable('fan_201301');

    /**
     * TODO Logic to Create New Table for each month
     * TODO Logic to Add Stats to proper table 
     */

    $albums = $db->GetAlbumIDs();
    foreach($albums as $album_ID){
    
    $album = $facebook->api($album_ID['album_ID'].'?fields=comments,likes,sharedposts,photos.fields(comments,likes,sharedposts)','GET');

            if($album['comments']){
                foreach($album['comments']['data'] as $albumComments){
                    $db->AddStats($albumComments['from']['id'], 'album_comments');
                }
            }

            if($album['likes']){
                foreach($album['likes']['data'] as $albumLikes){
                    $db->AddStats($albumLikes['id'], 'album_likes');
                }
            }

            if($album['sharedposts']){
                foreach($album['sharedposts']['data'] as $albumSharedPosts){
                    $db->AddStats($albumSharedPosts['from']['id'], 'album_shares');
                }
            }

            /**
             * @TODO for each photo in Album parse for coments, likes, shares
             */
            if($album['photos']){
                foreach($album['photos']['data'] as $photos){
                    if($photos['comments']){
                        foreach($photos['comments']['data'] as $photoComment){
                            $db->AddStats($photoComment['from']['id'], 'photo_comments');
                        }
                    }

                    if($photos['likes']){
                        foreach($photos['likes']['data'] as $photoLikes){
                            $db->AddStats($photoLikes['id'], 'photo_likes');
                        }
                    }

                    if($photos['sharedposts']){
                        foreach($photos['sharedposts']['data'] as $photoSharedPosts){
                            $db->AddStats($photoSharedPosts['from']['id'], 'photo_shares');
                        }
                    }
                }
            }
        }
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
        

    ?>

  	<!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>

  	</body>
</html>