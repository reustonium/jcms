<?php 
    require_once('facebook-php-sdk/facebook.php');
    require_once('facebook_credentials.php');
    require_once('application_variables.php');
    require_once('Database.class.php');
 
    $appUrl = 'http://alpineindie.com/jcms/webpage/makeBread.php';

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
         //TODO create php class for handling Facebook Auth
         if(empty($code)) {
           $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
           $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
             . APPID . "&redirect_uri=" . urlencode($appUrl) . "&state="
             . $_SESSION['state'] . "&scope=manage_pages,publish_stream";
           echo("<script> top.location.href='" . $dialog_url . "'</script>");
         }

         // Verify CSFR Protection and main webapp functionality
        echo($_SESSION['state']);
        echo('<hr>'.$_REQUEST['state']);
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
                // echo($page['name'].'<br>'.$page['id']);
                $pageID = $page['id'];
              } else {echo('no page match');}
            }
           
            // Update the Access Token to gain permission for the Page
            $pageToken = $facebook->api('/'.$pageID.'?fields=access_token','GET');
            $facebook->setAccessToken($pageToken['access_token']);

            // mySQL Connection
            $db = new Database();

            // Save DB and Facebook as SESSION variables for use in the createAlbum.php script
            $_SESSION['db'] = $db;
            $_SESSION['facebook'] = $facebook;

          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }
    ?>

    <form action="createAlbum.php" method="get">
        <input type="hidden" name="act" value="run">
        <input type="submit" value="Run me now!">
    </form>

  </body>
</html>