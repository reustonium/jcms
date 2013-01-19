<?php
	require_once("sql_credentials.php");

	Class Database
	{

		////////////////////
		// Sanitize DB Input
		////////////////////
		function SanitizeInput($input)
		{
			// first ensure there are escape chars
			$retVal = mysql_real_escape_string($input);
			
			// return the sanitized string
			return $retVal;
		}

		////////////////////
		// Connect to the DB
		////////////////////
		function Connect(){

			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");			

			// select the specified DB from the mysql instance
			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die ("Could not find (" . MYSQL_DATABASE . ") database.");	

			return $chandle;
		}

		//////////////////////
		// Execute Query of DB
		//////////////////////
		function Query($query)
		{
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this

			return $result;
		}
	}

?>