<?php
	require_once("sql_credentials.php");

	Class Database{

		/////////////////////////////////////////
		// Get the number of new photos in the DB
		/////////////////////////////////////////
		function CountNewPhotos(){

			$this -> Connect();

			// Create the Query String
			$query = "SELECT COUNT(*) AS loafs FROM ".MYSQL_TABLE." WHERE album_ID is NULL";

			// Execute the Query
			$results = $this -> Query($query);

			// Create the return value
			$retval = 0;

			// Get number of new photos in the DB
			$rowData = mysql_fetch_assoc($results);
			$retval = $rowData['loafs'];

			// Return Val
			return $retval;
		}

		////////////////////
		// Connect to the DB
		////////////////////
		function Connect(){

			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");			

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