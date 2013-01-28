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

		//////////////////////////////////
		// Get next n photos for the Album
		//////////////////////////////////
		function GetAlbumPhotos($numPhotos){
			
			$this -> Connect();

			// Create the Query String
			$query = "SELECT * FROM daily_bread WHERE album_ID IS NULL ORDER BY priority DESC LIMIT ".$numPhotos;

			// Execute the Query
			$results = $this -> Query($query);

			// Create the return Array
			$retval = array();

			// decode the rows
			while($r = mysql_fetch_assoc($results)) {

				// assign the values 
				$retval[]=$r;
			}

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
			$this->Connect();
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this

			return $result;
		}

		//////////////////////////
		// Check for Duplicate URL
		//////////////////////////
		function IsUrlDup($url)
		{
			$this->Connect();

			// Initialize number of Duplicates
			$numDup = 0;

			// Get all URLs of queued posts
			$query = "SELECT url FROM daily_bread WHERE album_ID IS NULL";
			$result = $this->Query($query);

			while($r = mysql_fetch_assoc($result)) {
				if($r['url']==$url){
					$numDup++;
				}
			}

			if ($numDup>0){
				return true;
			} else {
				return false;
			}
		}
	}

?>