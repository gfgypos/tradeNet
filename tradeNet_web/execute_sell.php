<?php
session_start();
require_once "db_connect.php";
// Request: Market Quotes (https://sandbox.tradier.com/v1/markets/quotes?symbols=spy)
$uid = $_SESSION['uid'];
$sym = $_POST['symbol'];
$share_price = $_POST['share_price'];
$num_shares = $_POST['num_shares'];
$purchased_at = $_POST['purchased_at'];
$total_cost = round($num_shares * $share_price, 2);
$current_date = date('Y-m-d H:i:s');
$stmt = $dbHandle->prepare("SELECT * FROM brokerage_user WHERE uid=:uid");
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$user_acct = $result['account_number'];
$user_bal = $result['balance'];

if($stmt = $dbHandle->prepare("SELECT shares FROM brokerage_portfolio where uid=:uid AND stock=:stock "))
{
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
 	$stmt->bindParam(':stock', $sym, PDO::PARAM_STR);
 	$stmt->execute();
 	$result = $stmt->fetch(PDO::FETCH_ASSOC);
 	if($result['shares'] != NULL && $num_shares <= $result['shares'])
 	{
 		if($stmt = $dbHandle->prepare("UPDATE brokerage_portfolio SET shares=shares-:num_shares, purchase_price = purchase_price-:total_cost WHERE uid=:user AND stock=:stock"))
 		{
 			$stmt->bindParam(':num_shares', $num_shares, PDO::PARAM_INT);
 			$stmt->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
 			$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
 			$stmt->bindParam(':stock', $sym, PDO::PARAM_STR);
 			$stmt->execute();
 		}

 		//insert into transaction table
	 	$stmt = $dbHandle->prepare("INSERT INTO brokerage_transactions ('uid', 'stock', 'shares_sold', 'transaction_amount', 'time_date') VALUES (':user', 'symbol', ':shares', ':cost', ':time_date')");
	 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 	$stmt->bindParam(':symbol', $sym, PDO::PARAM_STR);
	 	$stmt->bindParam(':shares', $num_shares, PDO::PARAM_INT);
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->bindParam(':time_date', $current_date, PDO::PARAM_STR);
	 	$stmt->execute();

	 	//update the brokerage_user table
	 	$stmt = $dbHandle->prepare("UPDATE brokerage_user SET balance=balance+:cost WHERE uid=:user");
	 	$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->execute();

		// if >
		if($purchased_at > $share_price)
		{
	 		$stmt = $dbHandle->prepare("UPDATE brokerage_user SET loss=loss+:cost WHERE uid=:user");
	 		$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 		$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 		$stmt->execute();
		}
	 	// if <
		else if($purchased_at < $share_price)
		{
			$stmt = $dbHandle->prepare("UPDATE brokerage_user SET profit=profit+:cost WHERE uid=:user");
	 		$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
	 		$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 		$stmt->execute();
		}
		else if($purchased_at == $share_price)
		{
			$stmt = $dbHandle->prepare("UPDATE brokerage_user SET loss=loss-:cost WHERE uid=:user");
			$stmt->bindParam(':user', $uid, PDO::PARAM_INT);
			$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
			$stmt->execute();
		}

	 	//update the account table
	 	$stmt = $dbHandle->prepare("UPDATE account SET account_bal=account_bal+:cost WHERE account_number=:acct");
	 	$stmt->bindParam(':cost', $total_cost, PDO::PARAM_STR);
	 	$stmt->bindParam(':acct', $user_acct, PDO::PARAM_INT);
	 	$stmt->execute();

		echo "<h1 style='text-align: center'>Transaction Successful</h1>";
		echo "<a style='text-align: center' href='sellstock.php'>Return to Selling Stocks</a>";
 	}
	else if($num_shares > $result['shares']){
		echo "<h1 style='text-align: center'>Can't sell more shares than you own!</h1>";
		echo "<a style='text-align: center' href='sellstock.php'>Return to Selling Stocks</a>";
	}
 	//deletes all entries if all shares are sold
 	$stmt = $dbHandle->prepare("DELETE FROM brokerage_portfolio WHERE shares=:zero");
 	$zero = 0;
 	$stmt->bindParam(':zero', $zero, PDO::PARAM_INT);
 	$stmt->execute();

}
else
{
	echo "<h1 style='text-align: center'>***Error in Transaction***</h1>";
	echo "<a style='text-align: center' href='sellstock.php'>Return to selling stocks</a>";
}
?>
