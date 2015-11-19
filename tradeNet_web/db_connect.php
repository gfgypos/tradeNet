<?php
/* Might need to change path */
//$dir = 'sqlite:bank_system.db';
//$dbHandle = new PDO($dir) or die("cannot open database");
class MyDB extends SQLite3 {
	function __construct($dbName) {
		$this->open($dbName . ".db");
	}
}
$dbHandle = new MyDB('bank_system');
?>
