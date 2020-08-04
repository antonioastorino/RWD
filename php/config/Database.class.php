<?php
class Database {
	private $host = 'db';
	private $username = 'root';
	private $password = 'root';
	private $dbName = 'ngcp_db';
	private $conn;

	public function connect() {
		// Create connection
		$conn = new mysqli(
			$this->host,
			$this->username,
			$this->password,
			$this->dbName
		);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$this->conn = $conn;
	}

	public function disconnect() {
		// Close connection
		$this->conn->close();
	}

	public function getConn() {
		return $this->conn;
	}
}
