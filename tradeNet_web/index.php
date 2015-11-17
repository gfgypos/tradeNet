<?php
require_once "db_connect.php";
$query = "SELECT * FROM account";
foreach ($dbHandle->query($query) as $row) {
	echo $row[0];
}
?>
