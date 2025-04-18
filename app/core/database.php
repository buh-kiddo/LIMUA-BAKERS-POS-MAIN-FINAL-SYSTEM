<?php


/**
 * database class
 */
class Database
{

	private function db_connect()
	{

		$DBHOST = "localhost";
		$DBNAME = "limuaa_db";
		$DBUSER = "root";
		$DBPASS = "";
		$DBDRIVER = "mysql";

		try {

			$con = new PDO("$DBDRIVER:host=$DBHOST;dbname=$DBNAME", $DBUSER, $DBPASS);
		} catch (PDOException $e) {

			echo $e->getMessage();
		}

		return $con;
	}


	public function query($query, $data = array())
	{
		$con = $this->db_connect();
		$stm = $con->prepare($query);

		$result = [];
		try {
			$check = $stm->execute($data);
			if ($check) {
				$result = $stm->fetchAll(PDO::FETCH_ASSOC);
				if (!is_array($result)) {
					$result = [];
				}
			}
		} catch (PDOException $e) {
			// Log error for debugging
			error_log("Database query error: " . $e->getMessage());
		}

		return $result;
	}
}
