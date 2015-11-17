<?php
$dir = 'sqlite:bank_system.db';
$dbh = new PDO($dir) or die("cannot open database");
$query = "SELECT * FROM account";
foreach ($dbh->query($query) as $row) {
	echo $row[0];
}
?>
