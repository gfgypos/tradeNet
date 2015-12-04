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
 		 	$current_date = date('Y-m-d H:i:s');
 		 	// this always returns true, even with no rows in db	
 		 	if($stmt = $dbHandle->prepare("SELECT shares FROM brokerage_portfolio where uid=:uid AND stock=:stock "))
 		 	{
 		 		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
 		 		$stmt->bindParam(':stock', $share_name, PDO::PARAM_STR);
 		 		$stmt->execute();
 		 		$result = $stmt->fetch(PDO::FETCH_ASSOC);	

 		 		$new_num_shares = $result['shares'] + $num_share;
				/* the way we have it set up, we store the individual stock purchase price, that way we can easily check to see if the newer price is > or < current market price for profit loss as well as display that info to users. Looks like you're storing TOTAL cost */
 		 		$stmt = $dbHandle->prepare("UPDATE brokerage_portfolio SET shares=:shares, purchase_price=purchase_price+:cost WHERE uid=:uid AND stock=:stock");
 		 		$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
 		 		$stmt->bindParam(':symbol', $share_name, PDO::PARAM_STR);
 		 		$stmt->bindParam(':shares', $new_num_share, PDO::PARAM_INT);
 		 		$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
 		 		$stmt->execute();
 		 	}
			/* I couldn't ever get this to return true or execute so some logic is funky around here */
 		 	else
 		 	{
 		 		$stmt = $dbHandle->prepare("INSERT INTO brokerage_portfolio VALUES (':user', ':symbol', ':shares', ':cost')");
 		 		$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
 		 		$stmt->bindParam(':symbol', $share_name, PDO::PARAM_STR);
 		 		$stmt->bindParam(':shares', $num_share, PDO::PARAM_INT);
 		 		$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
 		 		$stmt->execute();
 		 	}

 		 	//insert into transaction table
 		 	$stmt = $dbHandle->prepare("INSERT INTO brokerage_transactions ('uid', 'stock', 'shares_bought', 'transaction_amount', 'time_date') VALUES (':user', 'symbol', ':shares', ':cost', ':time_date')");
 		 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
 		 	$stmt->bindParam(':symbol', $share_name, PDO::PARAM_STR);
 		 	$stmt->bindParam(':shares', $num_share, PDO::PARAM_INT);
 		 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
 		 	$stmt->bindParam(':time_date', $current_date, PDO::PARAM_STR);
 		 	$stmt->execute();

 		 	//update the brokerage_user table
 		 	$stmt = $dbHandle->prepare("UPDATE brokerage_user SET balance=balance-:cost WHERE uid=:user");
 		 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
 		 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
 		 	$stmt->execute();

 		 	//update the account table
 		 	$stmt = $dbHandle->prepare("UPDATE account SET account_bal=account_bal-:cost WHERE account_number=:acct");
 		 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
 		 	$stmt->bindParam(':acct', $user_acct, PDO::PARAM_INT);
 		 	$stmt->execute();
			/* It will always say transaction successful even though these statements are failing */

 		 	echo "<h1 style='text-align: center'>Transaction Successful</h1>";
			echo "<a style='text-align: center' href='buystock.php'>Return to Searching for Stocks</a>";
 		 }
 		 else
 		 {
 		 	echo "<h1 style='text-align: center'>***Not Enough Funds***</h1>";
			echo "<a style='text-align: center' href='buystock.php'>Return to Searching for Stocks</a>";
 		 }

	}
	else
	{
		echo "<h1 style='text-align: center'>***Error in Transaction***</h1>";
		echo "<a style='text-align: center' href='buystock.php'>Return to Searching for Stocks</a>";
	}
	
?>
