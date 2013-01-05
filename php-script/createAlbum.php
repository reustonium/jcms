<? 
    $appId = '238552796278527';
    $appSecret = '83f3fa6948e194c5da23ca7297df6d02';
    $appUrl = 'http://alpineindie.com/jcms/createAlbum.php';

    session_start();
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
             . $_SESSION['state'] . "&scope=user_birthday,read_stream";

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

            $_SESSION['access_token'] = $params['access_token'];
            
            $endOfLife = $params['expires'];
            echo($endOfLife . '<br>');

            $graph_url = "https://graph.facebook.com/me?access_token=" 
            . $params['access_token'];

            $user = json_decode(file_get_contents($graph_url));
            echo("Hello " . $user->name);

            // Add cURL Code or php-sdk method to call graph-api

          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }
    ?>
  </body>
</html>