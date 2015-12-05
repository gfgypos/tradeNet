<?php
setlocale(LC_MONETARY, 'en_US');
session_start();
require_once "db_connect.php";
$uid = $_SESSION['uid'];
$query = $dbHandle->prepare("SELECT * from brokerage_portfolio WHERE uid=:uid");
$query->bindParam(':uid', $uid, PDO::PARAM_INT);
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
<h2>Current portfolio</h2>
  <p>These are the stocks you currently own along with purchase price and total current value.</p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Stock</th>
        <th>Purchase Price</th>
        <th>You own</th>
	<th>Current Market Price </th>
	<th>Total current value</th>
      </tr>
    </thead>
    <tbody>
<?php      while($result = $query->fetch(PDO::FETCH_ASSOC)){
/* adding stock query */
$sym = $result['stock'];
// Request quotes
$ch = curl_init("https://sandbox.tradier.com/v1/markets/quotes?symbols=${sym}");
//Headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"Accept: application/json",
	"Authorization: Bearer 5fXrPPE8pBIIOAGtmGLwn1Q1Z9sy",
));
//Send synchronously
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$result2 = curl_exec($ch);
//Failure
if($result2 === FALSE)
{
echo "cURL Error: " . curl_error($ch);
}
//Success
else
{
	$json = json_decode($result2);
	if(isset($json->quotes->quote)){
		$price = $json->quotes->quote->last;
	}

	echo '<tr><td>' . $result['stock'] . '</td>' .
	     '<td>$' . sprintf('%0.2f', $result['purchase_price']/$result['shares']) . '</td>' . 
	     '<td>' . $result['shares'] . ' shares</td>' .
	     '<td>$' . sprintf('%0.2f', $price) . '</td>' .
	     '<td>' . money_format('%i', $price*$result['shares']) . '</td></tr>';
	}
}
if(isset($ch)){
	curl_close($ch);
} else {
	echo '<b>No stocks found</b>';
}
?>
    </tbody>
  </table>
</div>
<?php $stmt = $dbHandle->prepare("SELECT profit, loss FROM brokerage_user WHERE uid=:uid");
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$result3 = $stmt->fetch(PDO::FETCH_ASSOC);
$net_profit = $result3['profit'] - $result3['loss'];
if($net_profit < 0)
{
	$net_profit = abs($net_profit);
	echo "<h2 style='text-align: center'><b>You have lost a total of $" . sprintf('%0.2f', $net_profit) . ".</b></h2>";
}
else if($net_profit > 0) 
{
	echo "<h2 style='text-align: center'><b>You have made a total of $" . sprintf('%0.2f', $net_profit) . ".</b></h2>";
}
else
{
	echo "<h2 style='text-align: center'><b>You have currently broke even on your investments.</b></h2>";
}
?>
</body>
</html>
