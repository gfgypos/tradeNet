<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
$uid = $_SESSION['uid'];
$sym = $_POST['symbol'];
$price = $_POST['share_price'];
$num_share = $_POST['num_shares'];
$total_cost = round($num_share * $share_price, 2);
$current_date = date('Y-m-d H:i:s');

if($stmt = $dbHandle->prepare("SELECT shares FROM brokerage_portfolio where uid=:uid AND stock=:stock "))
{
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
 	$stmt->bindParam(':stock', $sym, PDO::PARAM_STR);
 	$stmt->execute();
 	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
 	if($result['shares'] != NULL)
 	{
 		if($stmt = $dbHandle->prepare("UPDATE brokerage_portfolio SET shares = shares-:num_shares, purchase_price = purchase_price-:total_cost WHERE uid=:user AND stock=:sym"))
 		{
 			$stmt->bindParam(':num_shares', $num_shares, PDO::PARAM_INT);
 			$stmt->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
 			$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
 			$stmt->bindParam(':stock', $sym, PDO::PARAM_STR);
 			$stmt->execute();
 		}

 		//insert into transaction table
	 	$stmt = $dbHandle->prepare("INSERT INTO brokerage_transactions ('uid', 'stock', 'shares_sold', 'transaction_amount', 'time_date') VALUES (':user', 'symbol', ':shares', ':cost', ':time_date')");
	 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 	$stmt->bindParam(':symbol', $share_name, PDO::PARAM_STR);
	 	$stmt->bindParam(':shares', $num_share, PDO::PARAM_INT);
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->bindParam(':time_date', $current_date, PDO::PARAM_STR);
	 	$stmt->execute();

	 	//update the brokerage_user table
	 	$stmt = $dbHandle->prepare("UPDATE brokerage_user SET balance=balance+:cost WHERE uid=:user");
	 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->execute();

	 	//update the account table
	 	$stmt = $dbHandle->prepare("UPDATE account SET account_bal=account_bal+:cost WHERE account_number=:acct");
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->bindParam(':acct', $user_acct, PDO::PARAM_INT);
	 	$stmt->execute();
 	}

 	//deletes all entries if all shares are sold
 	$stmt = $dbHandle->prepare("DELETE FROM brokerage_portfolio WHERE shares=:zero");
 	$zero = 0;
 	$stmt->bindParam(':zeor', $zero, PDO::PARAM_INT);
 	$stmt->execute();
}
else
{
	echo "<h1 style='text-align: center'>***Error in Transaction***</h1>";
	echo "<a style='text-align: center' href='sellstock.php'>Return to selling stocks</a>";
}
?>
