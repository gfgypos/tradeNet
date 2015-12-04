<?php
	session_start();
	require_once "db_connect.php";
	$num_share = $_POST['num_shares'];
	$price_per_share = $_POST['share_price'];
	$uid = $_SESSION['uid'];
	$share_name = $_POST['symbol'];
	$total_cost = $price_per_share * $num_share;
/* USE THIS LINK FOR INSERT UPDATE AND DELETE 
http://www.mustbebuilt.co.uk/php/insert-update-and-delete-with-pdo/ 
*/
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
 		 	/* This always returns true, idk why, but we don't really need it because we don't care how many shares they already bought. Everytime a user buys shares we're going to create a new entry in the db or else we'll lose track of purchase price. Purchase price is going to per-share instead of grand total. We can easily find grand total later by doing purchase price * #of shares in a given row. We should go right into inserting the new entry in the db each time with an INSERT INTO statement. We'll only update in execute_sell in case someone sells 50/100 of their stocks etc etc */
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
			/* I couldn't ever get this to return true or execute so some logic is funky around here. For INSERT INTO the syntax is:
INSERT INTO table_name(table_col1, table_col2, ...) VALUES(:table_col1, :table_col2)  followed by the binding of parameters*/
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
