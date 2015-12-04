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
	<th>Total value</th>
      </tr>
    </thead>
    <tbody>
<?php      while($result = $query->fetch(PDO::FETCH_ASSOC)){
	echo '<tr><td>' . $result['stock'] . '</td>' .
	     '<td>$' . $result['purchase_price'] . '</td>' . 
	     '<td>' . $result['shares'] . ' shares</td>' .
	     '<td>Placeholder...</td>' .
	     '<td>' . money_format('%i', $result['purchase_price']*$result['shares']) . '</td></tr>';
	}
?>
    </tbody>
  </table>
</div>

</body>
</html>
