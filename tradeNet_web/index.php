<?php
require_once "db_connect.php";
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
 if($stmt = $dbHandle->prepare("SELECT * FROM brokerage_user WHERE username=:username AND password=:password"))
 {

  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->bindParam(':password', $password, PDO::PARAM_STR);

  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if($result['password'] == $password)
  {
    $_SESSION['username'] = $_POST['username'];
    header("Location: splash.php");
  }
}
 else
 {
    echo "<h1 class='text-center'>***Error in login credentials***</h1>";
    echo "<h2 class='text-center'>Please contact bank admin about setting up account</h2>";
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
                <form class="form-signin"  method="post">
                <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>
  </body>
</html>
