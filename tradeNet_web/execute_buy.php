<?php
	session_start();
	require_once "db_connect.php";
	$num_share = $_POST['num_shares'];
	$price_per_share = $_POST['share_price'];
	$uid = $_SESSION['uid'];
	$share_name = $_POST['symbol'];
	$total_cost = $price_per_share * $num_share;

	if($stmt = $dbHandle->prepare("SELECT * FROM brokerage_user WHERE uid=:uid"))
	{
		 $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
 		 $stmt->execute();
 		 $result = $stmt->fetch(PDO::FETCH_ASSOC);

 		 $user_acct = $result['account_number'];
 		 $user_bal = $result['balance'];
 		 if($user_bal >= $total_cost)
 		 {
 		 	$user_bal = $user_bal - $total_cost;
 		 	$current_date = date('Y-m-d H:i:s');
 		 }
 		 else
 		 {
 		 	echo "<h1 style='text-align: center'>***Not Enough Funds***</h1>";
			echo "<a href='buystock.php'>Return to Searching for Stocks</a>";
 		 }

	}
	else
	{
		echo "<h1 style='text-align: center'>***Error in Transaction***</h1>";
		echo "<a href='buystock.php'>Return to Searching for Stocks</a>";
	}
	
?>
