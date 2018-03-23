<?php
	class DB {
		private $db;

		public function __construct($host,$user,$pass,$database) {
			try {
				$this->db = mysqli_connect($host,$user,$pass,$database);
				mysqli_set_charset($this->db, 'utf8');
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
		}
		
		public function select_single($query) {
			$result = $this->db->query($query);
			$row    = $result->fetch_assoc();
			return !empty($row) ? $row : false;
		}
		
		public function select_multi($query) {
			$array = array();
			if ($result = $this->db->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$array[] = $row;
				}
				$result->free();
			}
			return !empty($array) ? $array : false;
		}
		
		public function query($query) {
			if ($query) {
				$this->db->query($query);
			}//end if

		}//end query()
		
		public function parseString($value) {
			return strip_tags($this->db->real_escape_string(htmlspecialchars($value)));
		}
		
		public function close() {
			return $this->db->close();
		}
	}
?>