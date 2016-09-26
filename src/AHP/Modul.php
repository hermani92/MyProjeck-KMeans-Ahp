<?php

class Modul{
	private $conn;
		
	public function __construct($db){
		$this->conn = $db;
	}
	
	function readAll($table_name){

		$query = "SELECT * FROM ".$table_name;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		return $stmt;
	}
	
	function getPerbandinganKriteria($table_name){
		
		$query = "SELECT * FROM ".$table_name;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		return $stmt;
	}
}