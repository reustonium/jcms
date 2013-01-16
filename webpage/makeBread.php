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
              } else {}
            }
            
            // Update the Access Token to gain permission for the Page
            $pageToken = $facebook->api('/'.$pageID.'?fields=access_token','GET');
            $facebook->setAccessToken($pageToken['access_token']);

            // mySQL Connection
            $db = new Database();

            $bread = $db->CountNewPhotos();

            // Save DB and Facebook as SESSION variables for use in the createAlbum.php script
            $_SESSION['db'] = $db;
            $_SESSION['facebook'] = $facebook;

          } else {  
            echo("The state does not match. You may be a victim of CSRF.");
          }
    ?>

    <div class="container-narrow">

        <h1>Johnny Cheeseburger's Daily Bread Factory</h1>

        <hr>

        <div class="jumbotron">
          <h2>Fresh Bread!</h2>
          <p class="lead">Clicking on the BAKE BREAD button will create a new 'Daily Bread' album and upload the next 5 images.</p>
          <br>
          <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Fact Check:</strong> You currently have <?php echo($bread); ?> unused photos in the database!
          </div>
          <form action="createAlbum.php" method="get">
            <button class="btn btn-large btn-success" type="submit">BAKE BREAD</button>
          </form>
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