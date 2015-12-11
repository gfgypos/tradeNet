<?php
session_start();
require_once "db_connect.php";
$query = "SELECT * FROM account";
?>
<!DOCTYPE html>
<html>
  <head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
      rel="stylesheet">
<link rel="stylesheet" href="main.css">
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

    <div class="jumbotron">
      <div class="container">
	<div class="text-center">
        <h1>Trade Net Stocks</h1>
	</div>
      </div>
    </div> 


    <div class="learn-more">
	  <div class="container">
		<div class ="row">
		 <div class = "col-md-4">
			<h3>View portfolio</h3>
			<p>View your currently owned stocks.</p>
			<p><a href="portfolio.php">Click here to view portfolio</a></p>
		  </div>
	      <div class = "col-md-4">
			<h3>Buy stocks</h3>
			<p>Click here to purchase stocks.</p>
			<p><a href="buystock.php">Click here to buy stocks</a></p>
	      </div>
		  <div class = "col-md-4">
			<h3>Sell stocks</h3>
			<p>Click here to sell your stocks.</p>
			<p><a href="sellstock.php">Click here to sell stocks</a></p>
		  </div>
	    </div>
	</div>
</div>
</body>
</html>
