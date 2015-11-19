<?php
session_start();
require_once "db_connect.php";

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
	<li><a href="splash.php">Home</a> | <a href="buystock.php">Buy stocks</a> | <a href="sellstock.php">Sell Stock</a> | <a href="#">View Portfolio</a></li>
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
        <th>Price</th>
        <th>Click below to sell your stocks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>GOOGLE</td>
        <td>$2bazillion</td>
        <td><a href="#">Sell now</td>
      </tr>
      <tr>
        <td>FIREFAX</td>
        <td>-3</td>
        <td><a href="#">Sell now</td>
      </tr>
      <tr>
        <td>your mom</td>
        <td>priceless</td>
        <td><a href="#">Sell now</td>
      </tr>
    </tbody>
  </table>
</div>

</body>
</html>
