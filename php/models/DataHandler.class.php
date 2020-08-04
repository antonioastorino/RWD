<?php
class DataHandler {
	private $conn = null;
	public function __construct($conn) {
		$this->conn = $conn;
	}

	public function insertRadio($radio_id, $alias, $locations) {
		// Insert radio in `radios` DB
		$sql = "INSERT INTO `radios` (`radio_id`, `alias`) VALUES (" . $radio_id . ", '" . $alias . "')";
		if (!$this->conn->query($sql)) {
			return 403;
		}

		foreach ($locations as $location) {
			// Insert radio_id and location in `allowed_locations` DB
			$sql = "INSERT INTO `allowed_locations` (`radio_id`, `location`) VALUES (" . $radio_id . ", '" . $location . "')";
			$this->conn->query($sql);
		}
		return 200;
	}

	public function setLocation($radio_id, $location) {
		// Get allowed locations for the radio with ID $radio_id
		$sql = "SELECT `location` FROM `allowed_locations` WHERE `radio_id` = " . $radio_id;
		$result = $this->conn->query($sql);
		$allowedLocations = array();
		if ($result->num_rows > 0) {
			// output data of each row
			while ($row = $result->fetch_assoc()) {
				$allowedLocations[] = $row["location"];
			}
		}
		// Check that the location we want to assign is among the allowed ones
		if (!in_array($location, $allowedLocations)) {
			return 403;
		} else {
			// Assign the location $location to the radio with ID $radio_id
			$sql = "UPDATE `radios` SET `location` = '" . $location . "' WHERE `radios`.`radio_id` = " . $radio_id;
			if (!$this->conn->query($sql)) {
				return 403;
			}
		}
		return 200;
	}

	public function getLocation($radio_id, &$location_out) {
		$sql = "SELECT `location` FROM `radios` WHERE `radio_id` = " . $radio_id;
		$result = $this->conn->query($sql);
		$value = $result->fetch_object();
		if ($result->num_rows > 0) {
			$location_out = $value->location;
			if ($location_out == null) {
				return 404;
			}
		} else {
			return 404;
		}
		return 200;
	}
}
