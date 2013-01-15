<?php 
    require_once('facebook-php-sdk/facebook.php');
   // require_once('facebook_credentials.php');
   // require_once('application_variables.php');
    require_once('Database.class.php');

    session_start();

    echo("you've arrived");
    echo("<hr>");
    $db = $_SESSION['db'];
    $result = $db->CountNewPhotos();
    echo($result.'<hr>');

    $facebook = $_SESSION['facebook'];
    $id = $facebook->api('me', 'GET');
    echo $id['id'];


    // // Retrieve SESSION variables for db and facebook
    // $db = $_SESSION['db'];
    // $facebook = $_SESSION['facebook'];
    
    // // Create New Album and update bread_history DB
    // $album = $facebook->api('/me/albums','POST',array('name'=>'Cheeseburger Daily Bread '.date('F j, Y - H:i'), 'description'=>'Daily Bread'));
    // $db->Query("INSERT INTO bread_history (album_ID, date) VALUES (".$album['id'].",'".date('Y-m-d')."')");
    
    // // Add Photos and update daily_bread DB
    // $result = $db->GetAlbumPhotos(3);
    // while($row = mysql_fetch_array($result))
    // {              
    //   // Upload photo to album
    //   $photo = $facebook->api('/'.$album['id'].'/photos','POST',array('url'=>$row['url'],'name'=>$row['comment'],'no_story'=>'1'));

    //   // update DB
    //   $db->Query("UPDATE daily_bread SET album_ID='".$album['id']."' WHERE uid=".$row['uid']);
    // } 

    // // Create Post for Front Page
    // $postInfo = $facebook->api('/'.$album['id'].'/?fields=link,name','GET');
    // $post = $facebook->api('/me/feed','POST',array('link'=>$postInfo['link'],'description'=>'Daily Bread'));

    // // Send email to JC
    // $queryData = mysql_query("SELECT COUNT(*) AS loafs FROM daily_bread WHERE album_ID is NULL");
    // $rowData = mysql_fetch_assoc($queryData);
    // $numBread = $rowData['loafs'];

    // $emailMessage = 'you have enough bread for '.floor($numBread/5).' moar days. Without more bread your fans will starve on: '.date("F j, Y", strtotime("+".(floor($numBread/5)+1)."day"));
    // $sentMail = mail($emailAddress,$emailSubject,$emailMessage);

    // //close mysql connection
?>