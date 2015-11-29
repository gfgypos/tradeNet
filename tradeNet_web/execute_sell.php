<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
$sym = $_POST['symbol'];
	$ch = curl_init("https://sandbox.tradier.com/v1/markets/quotes?symbols=${sym}");

	// Headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Accept: application/json",
	  "Authorization: Bearer 5fXrPPE8pBIIOAGtmGLwn1Q1Z9sy",
	));

	// Send synchronously
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$result = curl_exec($ch);

	// Failure
	if ($result === FALSE)
	{
	  echo "cURL Error: " . curl_error($ch);
	}

	// Success
	else
	{
 	 $json = json_decode($result);
	/* DEBUG ONLY */  
//	print_r($json);
	  //echo "Request completed: " . $json->quotes->quote->symbol;
	if (isset($json->quotes->quote)){
		  $price = $json->quotes->quote->open;
		  $symbol = $json->quotes->quote->symbol;
		  $symbolName = $json->quotes->quote->description;
	}	
	}
	curl_close($ch);
echo $sym;
?>
