<?php 
    require_once('facebook-php-sdk/facebook.php');
    require_once('facebook_credentials.php');
    require_once('application_variables.php');
 
    $appUrl = 'http://alpineindie.com/jcms/webpage/createAlbum.php';

    session_start();

    $facebook = new Facebook(array(
                                  'appId'=>APPID,
                                  'secret'=>APPSECRET));
?>

<html>
  <head></head>
  <body>
    <?php
       $code = $_REQUEST["code"];

         //Grab access code 
         if(empty($code)) {
           $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
           $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
             . APPID . "&redirect_uri=" . urlencode($appUrl) . "&state="
             . $_SESSION['state'] . "&scope=manage_pages,publish_stream";

           echo("<script> top.location.href='" . $dialog_url . "'</script>");
         }

         // Verify CSFR Protection and main webapp functionality
         if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
            $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=" . APPID . "&redirect_uri=" . urlencode($appUrl)
            . "&client_secret=" . APPSECRET . "&code=" . $code;

            $response = file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            // Set session to User Access Token to find Page Access
            $facebook->setAccessToken($params['access_token']);
            $pages = $facebook->api('/me/accounts?fields=name,id','GET');

            // Find the ID for the proper page
            foreach($pages['data'] as $page){
              if ($page['name']== PAGENAME){
                echo($page['name'].'<br>'.$page['id']);
                $pageID = $page['id'];
              } else {echo('no page match');}
            }
           
            // Update the Access Token to gain permission for the Page
            $pageToken = $facebook->api('/'.$pageID.'?fields=access_token','GET');
            $facebook->setAccessToken($pageToken['access_token']);

            // mySQL Connection
            // TODO replace with instatiation of Database Class
            $con = mysql_connect("localhost:3306","aplinein_jc","pizzacake");
            if (!$con){
              die('Could not connect: ' . mysql_error());
            }
            mysql_select_db("aplinein_dailybread", $con);
            
            // Create New Album and update bread_history DB
            $album = $facebook->api('/me/albums','POST',array('name'=>'Cheeseburger Daily Bread '.date('F j, Y - H:i'), 'description'=>'Daily Bread'));
            mysql_query("INSERT INTO bread_history (album_ID, date) VALUES (".$album['id'].",'".date('Y-m-d')."')");
            
            // Add Photos and update daily_bread DB
            $result = mysql_query("SELECT * FROM daily_bread WHERE album_ID IS NULL LIMIT 2");
            while($row = mysql_fetch_array($result))
            {              
              // Upload photo to album
              $photo = $facebook->api('/'.$album['id'].'/photos','POST',array('url'=>$row['url'],'name'=>$row['comment'],'no_story'=>'1'));

              // update DB
              mysql_query("UPDATE daily_bread SET album_ID='".$album['id']."' WHERE uid=".$row['uid']);
            } 

            // Create Post for Front Page
            $postInfo = $facebook->api('/'.$album['id'].'/?fields=link,name','GET');
            $post = $facebook->api('/me/feed','POST',array('link'=>$postInfo['link'],'description'=>'Daily Bread'));

            // Send email to JC
            $queryData = mysql_query("SELECT COUNT(*) AS loafs FROM daily_bread WHERE album_ID is NULL");
            $rowData = mysql_fetch_assoc($queryData);
            $numBread = $rowData['loafs'];

            $emailMessage = 'you have enough bread for '.floor($numBread/5).' moar days. Without more bread your fans will starve on: '.date("F j, Y", strtotime("+".(floor($numBread/5)+1)."day"));
            $sentMail = mail($emailAddress,$emailSubject,$emailMessage);

            mysql_close($con);

          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }
    ?>
  </body>
</html>