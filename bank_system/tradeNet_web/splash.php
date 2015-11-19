<?php
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

    <div class="jumbotron">
      <div class="container">
	<div class="text-center">
        <h1>Sweg Stocks</h1>
	</div>
      </div>
    </div> 


    <div class="learn-more">
	  <div class="container">
		<div class ="row">
		 <div class = "col-md-4">
			<h3>View portfolio</h3>
			<p>View your currently owned stocks.</p>
			<p><a href="#">Click here to get started</a></p>
		  </div>
	      <div class = "col-md-4">
			<h3>Buy stocks</h3>
			<p>Click here to purchase stocks.</p>
			<p><a href="#">Click here to upload content</a></p>
	      </div>
		  <div class = "col-md-4">
			<h3>Sell stocks</h3>
			<p>Click here to sell your stocks.</p>
			<p><a href="#">Click here to view user-submitted content</a></p>
		  </div>
	    </div>
	</div>
</div>
</body>
</html>
