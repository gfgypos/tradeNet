<?php
require_once "db_connect.php";
if(isset($_POST['username']) AND isset($_POST['password'])){
	$username = $_POST['username'];
	$result = $dbHandler->query("SELECT password WHERE username='$username'");
	if($result->rowCount() > 0){
		$_SESSION['username'] = $_POST['username'];
		header("Location:'index.php'");
	}
}
?>
<!DOCTYPE html>
<html>

  <head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
      rel="stylesheet">
<link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Sign in to continue to Sweg Banking</h1>
            <div class="account-wall">
                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                <form class="form-signi" action="login.php" method="post">
                <input type="text" class="form-control" placeholder="Username" required autofocus>
                <input type="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>
  </body>
</html>
