<? 
    require_once('facebook-php-sdk/facebook.php');
    $appId = '238552796278527';
    $appSecret = '83f3fa6948e194c5da23ca7297df6d02';
    $appUrl = 'http://alpineindie.com/jcms/createAlbum.php';
    $pageName = 'Reustonium';

    session_start();

    $facebook = new Facebook(array(
                                  'appId'=>$appId,
                                  'secret'=>$appSecret));

    // mySQL Connection
    $con = mysql_connect("localhost:3306","aplinein_jc","pizzacake");
    if (!$con){
      die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("aplinein_dailybread", $con);
?>

<html>
  <head></head>
  <body>
    <?
       $code = $_REQUEST["code"];

         //Grab access code 
         if(empty($code)) {
           $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
           $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
             . $appId . "&redirect_uri=" . urlencode($appUrl) . "&state="
             . $_SESSION['state'] . "&scope=manage_pages,publish_stream";

           echo("<script> top.location.href='" . $dialog_url . "'</script>");
         }

         // Verify CSFR Protection
         if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
            $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=" . $appId . "&redirect_uri=" . urlencode($appUrl)
            . "&client_secret=" . $appSecret . "&code=" . $code;

            $response = file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);

            // Set session to User Access Token to find Page Access
            $facebook->setAccessToken($params['access_token']);
            $pages = $facebook->api('/me/accounts?fields=name,id','GET');

            // Find the ID for the proper page
            foreach($pages['data'] as $page){
              if ($page['name']==$pageName){
                echo($page['name'].'<br>'.$page['id']);
                $pageID = $page['id'];
              } else {echo('no page match');}
            }
           
            // Update the Access Token for the Page
            $pageToken = $facebook->api('/'.$pageID.'?fields=access_token','GET');
            $facebook->setAccessToken($pageToken['access_token']);
            
            // Create New Album
            $album = $facebook->api('/me/albums','POST',array('name'=>'Cheeseburger Daily Bread '.date('F j, Y - H:i'), 'description'=>'Daily Bread'));
            
            // Add Photos and update DB
            $result = mysql_query("SELECT * FROM daily_bread WHERE album_ID IS NULL LIMIT 2");
            while($row = mysql_fetch_array($result))
            {
              echo "<br />";
              echo 'comment: '.$row['comment']. ' uid: '.$row['uid'];
          
              $photo = $facebook->api('/'.$album['id'].'/photos','POST',array('url'=>$row['url'],'name'=>$row['comment']));

              // update DB
              mysql_query("UPDATE daily_bread SET album_ID='".$album['id']."' WHERE uid=".$row['uid']);
            } 


            // Create Post for Front Page
            $postInfo = $facebook->api('/'.$album['id'].'/?fields=link,name','GET');
            $post = $facebook->api('/me/feed','POST',array('link'=>$postInfo['link']));

            // Send email to JC
          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }

          mysql_close($con);
    ?>
  </body>
</html>