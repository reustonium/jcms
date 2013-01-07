<? 
    require_once('facebook-php-sdk/facebook.php');
    $appId = '238552796278527';
    $appSecret = '83f3fa6948e194c5da23ca7297df6d02';
    $appUrl = 'http://alpineindie.com/jcms/createAlbum.php';

    session_start();

    $facebook = new Facebook(array(
                                  'appId'=>$appId,
                                  'secret'=>$appSecret));
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

            // Add cURL Code or php-sdk method to call graph-api
            $facebook->setAccessToken($params['access_token']);

            $graph_url = "https://graph.facebook.com/me?access_token=" 
            . $params['access_token'];

            $user = json_decode(file_get_contents($graph_url));
            echo("<br>Hello " . $user->name);

            $ret_obj = $facebook->api('/me/feed', 'POST',
                                    array(
                                      'link' => 'www.example.com',
                                      'message' => 'Posting with the PHP SDK!'
                                 ));
            echo '<br><pre>Post ID: ' . $ret_obj['id'] . '</pre>';

            $pages = $facebook->api('/me/accounts?fields=name,id','GET');

            $pageID = $pages['data'][0]['id'];

            $pageToken = $facebook->api('/'.$pageID.'?fields=access_token','GET');
            $facebook->setAccessToken($pageToken['access_token']);
            $newAlbumId = $facebook->api('/me/albums','POST', array('name'=>'fresh','description'=>'stuffz'));
            $newPhoto = $facebook->api('/'.$newAlbumId['id'].'/photos','POST',array('url'=>'http://profile.ak.fbcdn.net/hprofile-ak-ash4/369308_502360721_167399057_q.jpg','name'=>'drewski'));
 
          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }
    ?>
  </body>
</html>