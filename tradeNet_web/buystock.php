<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)

$ch = curl_init("https://sandbox.tradier.com/v1/markets/quotes?symbols=GOOG");

// Headers

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Accept: application/json",
  "Authorization: Bearer 5fXrPPE8pBIIOAGtmGLwn1Q1Z9sy",
));

// Send synchronously

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
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
print_r($json);
  echo "Request completed: " . $json->quotes->quote->symbol;
}

curl_close($ch);

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
	<li><a href="splash.php">Home</a> | <a href="buystock.php">Buy stocks</a> | <a href="sellstock.php">Sell Stock</a> | <a href="#">View Portfolio</a></li>
	</ul>
	</div>
</div>
<div class ="container">
	<div class="row">
		<h2>Search for stocks to purchase</h2><br>
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

<div class="container">
<br><br><br><br>
  <h2>Results</h2>
  <p>We found these stocks matching your search criteria</p>            
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Stock</th>
        <th>Price</th>
        <th>Click below to begin purchase</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>GOOGLE</td>
        <td>$2bazillion</td>
        <td><a href="#">Buy now</td>
      </tr>
      <tr>
        <td>FIREFAX</td>
        <td>-3</td>
        <td><a href="#">Buy now</td>
      </tr>
      <tr>
        <td>your mom</td>
        <td>priceless</td>
        <td><a href="#">Buy now</td>
      </tr>
    </tbody>
  </table>
</div>

</body>
</html>
