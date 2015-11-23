<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
if(isset($_GET['buyingstock'])){
	$sym = $_GET['buyingstock'];
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
//	  print_r($json);
	 // echo "Request completed: " . $json->quotes->quote->symbol;
	  $price = $json->quotes->quote->open;
	  $symbol = $json->quotes->quote->symbol;
	  $symbolName = $json->quotes->quote->description;
	}
	curl_close($ch);
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
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
<div class ="container">
	<div class="row">
		<h2>Please enter a valid stock symbol</h2><br>
		<form class="search-stocks" method="GET">
		<div id="custom-search-input">
		<div class="input-group col-md-12">
			<input type="text" class="form-control input-lg" placeholder="Search for stocks..." name="buyingstock"/>
			<span class="input-group-btn">
				<button class="btn btn-info btn-lg" type="submit">
					<i class="glyphicon glyphicon-search"></i>
				</button>
			</span>
		</form>
		</div>
		</div>
		</div>
	</div>
</div>

<?php if(isset($_GET['buyingstock'])){ ?>
<div class="container">
<br><br><br><br>
  <h2>Result</h2>
  <p>We found this stock matching your search criteria</p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Stock</th>
        <th>Price per share</th>
        <th>Click below to begin purchase</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo '('. $symbol . ')' . '   ' . $symbolName ?></td>
        <td><?php echo '$' . $price ?></td>
        <td><a href="confirmation.php">Buy now</td>
      </tr>
    </tbody>
  </table>
</div>
<?php } ?>
</body>
</html>
