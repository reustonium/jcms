<?php

	require_once("Database.class.php");

	class PhotoTool
	{
	
		/////////////////////////////////////////
		// Get the number of new photos in the DB
		/////////////////////////////////////////
		function CountNewPhotos()
		{

			$db = new Database();
			$db -> Connect();

			// Create the Query String and execute it
			$query = "SELECT COUNT(*) AS loafs FROM ".MYSQL_TABLE." WHERE album_ID is NULL";
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
		function GetAlbumPhotos($numPhotos)
		{
			
			$db = new Database();
			$db -> Connect();

			// Create the Query String and execute it
			$query = "SELECT * FROM daily_bread WHERE album_ID IS NULL LIMIT ".$numPhotos;
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
	}

?>