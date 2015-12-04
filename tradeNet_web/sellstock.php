<?php
session_start();
require_once "db_connect.php";
$query = $dbHandle->prepare("SELECT * from brokerage_portfolio");
$query->execute();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" type="text/css" href="buystock.css">
</head>
<body>

<?php echo "<br><b>You are logged in as " . $_SESSION['username'] . ".</b>";?>
<div class="nav">
	<div class="container">
	<ul class ="pull-right">
	<li><a href="splash.php">Home</a> | <a href="buystock.php">Buy stocks</a> | <a href="sellstock.php">Sell Stock</a> | <a href="portfolio.php">View Portfolio</a> | <a href="logout.php">Logout</a></li>
	</ul>
	</div>
</div>

<div class="container">
<br>  
<h2>Stocks owned by you</h2>
  <p>These are the stocks you currently own.</p>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Stock</th>
        <th>Purchase Price</th>
        <th>You own</th>
	<th>Current Market Price </th>
	<th>Use links below to sell</th>
      </tr>
    </thead>
    <tbody>
<?php

while($result = $query->fetch(PDO::FETCH_ASSOC)){
$sym = $result['stock'];
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
$ch = curl_init("https://sandbox.tradier.com/v1/markets/quotes?symbols=${sym}");

// Headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Accept: application/json",
	"Authorization: Bearer 5fXrPPE8pBIIOAGtmGLwn1Q1Z9sy",
));

// Send synchronously
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result2 = curl_exec($ch);

// Failure
if ($result2 === FALSE)
{
  echo "cURL Error: " . curl_error($ch);
}

// Success
else
{
	$json = json_decode($result2);
/* DEBUG ONLY */  
//print_r($json);
//echo "Request completed: " . $json->quotes->quote->symbol;
	if (isset($json->quotes->quote)){
		$price = $json->quotes->quote->open;
	}	
}
?>
<form action="execute_sell.php" method="POST"> <?php
	echo '<tr><td>' . $result['stock'] . '</td>' .
	     '<td>$' . $result['purchase_price'] . '</td>' . 
	     '<td>' . $result['shares'] . ' shares</td>' .
	     '<td>$' . $price . '</td>' .
	     '<td><input type="text" name="num_shares" size="3" /></td>' .
	     '<td><input type="submit" value="Sell now"/></td>';
?> <input type="hidden" name="share_price" value="<?php echo $price; ?>"/>
<input type="hidden" name="symbol" value="<?php echo $result['stock']; ?>"/>
</form>
<?php
	}
curl_close($ch);

?>
    </tbody>
  </table>
</div>

</body>
</html>
