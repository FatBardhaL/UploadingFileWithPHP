<?php
	require_once("config.php");
		// Database Class::
	class Database{
		//Deklarimi i variablave te database:
		public $host     = DB_HOST;
		public $user     = DB_USER;
		public $password = DB_PASS;
		public $dbname   = DB_NAME;

		public $link;
		public $error;
			// Konstruktori aktivizohet gjithmon edhe nese klasa nuk thirret.
		function __construct(){
			$this->connectDB();
		}

		private function connectDB(){
				// Create connection : $conn = new mysqli($servername, $username, $password);
			$this->link = new mysqli($this->host,$this->user,$this->password,$this->dbname);
				//
			if (!$this->link) {
					//Kur perdorim new mysqli::$connect_error -- mysqli_connect_error — 
					//Ateher connect_error->Returns a string description of the last connect error
			 	$this->error = "Connection failed. -> ".$this->link->connect_error;
			 	echo "Lidhja me database nuk eshte realizuar.";
			}else{
				echo "Lidhja me database eshte realizuar.";
			} 
		}
		// Insert Data:
		public function insert($data){
											//__LINE__ – The current line number of the file.
			$insert_row = $this->link->query($data) or die($this->link->error.__LINE__);
			if ($insert_row) {
				// echo "<br/>....insert_row";
				return $insert_row;
			}else{
				return false;
			}
		}
		//Select Data:
		public function select($data){
											//__LINE__ – The current line number of the file.
			$result = $this->link->query($data) or die($this->link->error.__LINE__);
				//mysqli_result::$num_rows -- mysqli_num_rows — 
				//num_rows->Gets the number of rows in a result
			if ($result->num_rows > 0) {
				// echo "....result";
				return $result;
			}else{
				return false;
			}
		} 
		//Delete Data:
		public function delete($data){
											//__LINE__ – The current line number of the file.
			$delete_row = $this->link->query($data) or die($this->link->error.__LINE__);
			if ($delete_row) {
				return $delete_row;
			}else{
				return false;
			}
		} 
	}
?>