<? 
  require_once('facebook-php-sdk/facebook.php');

  $config = array();
  $config[‘appId’] = '238552796278527';
  $config[‘secret’] = '83f3fa6948e194c5da23ca7297df6d02';

  $facebook = new Facebook($config);
?>

<html>
	<head></head>
	<body>
		<?
			//Check for user
			$code = $_REQUEST["code"];

         	//Grab access code 
         	if(empty($code)) {
				 $loginParams = array(
				 	'scope'=>'manage_pages, publish_upstream',
				 	'redirect_uri'=>'http://alpineindie.com/jcms/makeBread.php');
				 $loginUrl = $facebook->getLoginUrl($loginParams);
				 echo($loginUrl);
  	             echo("<script> top.location.href='" . $login_url . "'</script>");
			}
			//echo('hello: '.$uid." you're cool");
		?>
	</body>
</html>