<?php
$con = mysql_connect("localhost:3306","aplinein_jc","pizzacake");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("aplinein_dailybread", $con);

$sql="INSERT INTO test (url, comment)
VALUES
('$_POST[url]','$_POST[comment]')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "Successfully Added to the JC Queue";

mysql_close($con);
?>