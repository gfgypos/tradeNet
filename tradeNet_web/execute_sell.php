<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
$sym = $_POST['symbol'];
$price = $_POST['share_price'];
$num_share = $_POST['num_shares'];
echo $sym . '<br>';
echo $price . '<br>';
echo $num_share;
?>
