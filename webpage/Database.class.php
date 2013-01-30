<?php
/**
 * @package Default
 * @author Reustonium
 */


/**
 * Imports SQL database Credentials
 * 
 * 
 * Import of constants
 * * MYSQL_DATABASE
 * * MYSQL_TABLE
 * * MYSQL_HOST
 * * MYSQL_USER
 * * MYSQL_PASS
 */
	require_once("sql_credentials.php");

	/**
	 * Database Class for communication with mySQL DB
	 * 
	 * 
	 * This class handles connecting with mySQL databases
	 * as well as handling and returning query functions
	 * and project specific functions.
	 */
	Class Database{

		/**
		 * Count Unused Photos in DB
		 * 
		 * This function returns the number of photos which
		 * have not yet been posted to the fan page.
		 * 
		 * 
		 * @return int number of new photos
		 */
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

		/**
		 * Get Photos to Post to the Album
		 * 
		 * 
		 * This function returns an array of url's and comments for a user
		 * specified number of photos to add to a fanpage album.  $numPhotos 
		 * dictates the number of images to pull from the database.  The query also
		 * takes advantage of the 'priority' field which is user selectable in the
		 * Chrome extension.
		 * 
		 * 'Priority' marked images are returned before images whose 'priority' field 
		 * are = 0
		 * 
		 * @param int $numPhotos The number of photos to return from the database.
		 * @return array url's and comments
		 */
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

		/**
		 * Connect to the mySQL database
		 * 
		 * This function uses the CONSTANTS from sql_credentials to connect
		 * to the mySQL database and return a database handle for making 
		 * queries.  If a connection cannot be made an error is returned.
		 * 
		 * @return handle mySQL handle
		 * @throws ConnectionError Could not find database.
		 */
		function Connect(){

			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");			

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die ("Could not find (" . MYSQL_DATABASE . ") database.");	

			return $chandle;
		}

		/**
		 * Query the DB
		 * 
		 * This function connects to the DB and executes a query based on the query input string,
		 * it returns the result of the query.
		 * 
		 * @param string $query Query string
		 * @return databasequeryobject result of the query 
		 * @throws QueryError Failed to Query $query. 
		 */
		function Query($query)
		{
			$this->Connect();
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  		
			return $result;
		}

		/**
		 * Check for Duplicate Images in the DB
		 * 
		 * This function compares the $url parameter to all url's in the DB which
		 * have not yet been posted.  If the input url is a match to an existing url
		 * in the database the function returns == TRUE == . 
		 * 
		 * @param string $url url of photo to check against entries in the DB.
		 * @return boolean *True: If the images is a duplicate. *False: If the image is unique.
		 */
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

		/**
		 * Update Fan Statistics
		 * 
		 * This function updates the fan statistics database by first pulling all AlbumID's
		 * from the history table then parsing for user interactions related to each album.
		 * Currently the following user interactions are tracked:
		 * * album_likes 
		 * * album_comments 
		 * * album_shares
		 * * photo_likes 
		 * * photo_comments 
		 * * photo_shares 
		 * 
		 * TODO: Create Fan Ranking Algorithm
		 * 
		 * @param int $fb_id Facebook ID of the user
		 * @param string $field Stat Database field to update. 
		 */
		function AddStats($fb_id, $field){
			$this->Connect();

			// Check for New User ID
			if($this->isNewUser($fb_id) == 'true'){
				$query = "INSERT INTO fan_201301 (facebook_id, ".$field.") VALUES (".$fb_id.",1)";
			} else {
				$query = "UPDATE fan_201301 SET ".$field."=".$field."+1 WHERE facebook_id=".$fb_id;
			}
			
			$this->Query($query);
		}

		/**
		 * Clears the Fan Statistics Table
		 * 
		 * This is a utility function which clears all rows from a specified $tableName
		 * 
		 * @param $tableName Name of the Table to clear. Format: YYYYMM
		 */
		function ClearTable($tableName){
			$this->Connect();

			$query = "DELETE FROM ".$tableName." WHERE facebook_id IS NOT NULL;";
			$this->Query($query);
		}

		/**
		 * Check to see if the User is already in the Database
		 * 
		 * This function checks to see if a user is already in the Fan Statistics
		 * database.  If the user is NOT in the database the function returns ## TRUE ##
		 * 
		 * @param int $fid Facebook user id
		 */
		function isNewUser($fid){
			$this->Connect();

			$query = "SELECT * FROM fan_201301 WHERE facebook_id = ".$fid;
			$result = $this->Query($query);

			$r = mysql_fetch_assoc($result);

			if($r){return 'false';}
			else{return 'true';}
		}

		/**
		 * Returns the all AlbumID's which have been posted with JCMS
		 * 
		 * This function returns all Album ID's for albums which have been posted
		 * with JCMS, the album ID's are returned from the bread_history table.
		 * 
		 * @return array(int) List of all album ID's. 
		 */
		function GetAlbumIDs(){
			$this->Connect();

			$query = "SELECT album_ID FROM bread_history";
			$result = $this->Query($query);

			// Create the return Array
			$retval = array();

			// decode the rows
			while($r = mysql_fetch_assoc($result)) {

				// assign the values 
				$retval[]=$r;
			}

			// Return Val
			return $retval;

		}
	}

?>