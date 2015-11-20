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
<?php      while($result = $query->fetch(PDO::FETCH_ASSOC)){
	echo '<tr><td>' . $result['stock'] . '</td>' .
	     '<td>$' . $result['purchase_price'] . '</td>' . 
	     '<td>' . $result['shares'] . ' shares</td>' .
	     '<td>Placeholder...</td>' .
	     '<td><a href="confirmation.php">Sell now</td></tr>';
	}
?>
    </tbody>
  </table>
</div>

</body>
</html>
