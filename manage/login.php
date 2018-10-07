<?php
session_start();
include_once "../php_lib/pdo.inc.php";

try {

	//throw new Error('Generic error.');
	// throw new Exception('Generic exception.');

	if (isset($_POST["account"]) && isset($_POST["pw"])) {

		// Logout admin

		unset($_SESSION["account"]);
		$account = $_POST["account"];
		$pw = md5($_POST["pw"]); //md5
		try {
			$sql = "SELECT * FROM users 
                    WHERE username = :username
                    AND password = :password";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':username' => $account,
				':password' => $pw
			));
			$count = $stmt->rowCount();
			if ($count > 0) {

				// loginsession for all the quizzes

				$_SESSION["browser"] = true;
				$_SESSION["account"] = $account;
				$_SESSION["password"] = $pw;
				$_GET["success"] = "Logged in.";

				// $_SESSION["success"] = "Logged in.";

				header("Location: app.php?checkWarning=on");
				return;
			}
			else {
				$_SESSION["error"] = "Incorrect Password or Username.";
				header('Location: login.php');
				return;
			}
		} //end try
		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}
	}
} //try
catch(Exception $e) {
	include ("../php_lib/myExceptionHandling.inc.php");
	echo myExceptionHandling($e, "../logs/error_log.csv");
}

catch(Error $e) {
	include ("../php_lib/myExceptionHandling.inc.php");
	echo myExceptionHandling($e, "../logs/error_log.csv");
}

?>
    <!DOCTYPE html>
    <html lang="en">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <head>
        <title>
            Simple Quiz - login
        </title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
    </head>

    <body style="font-family: sans-serif;">
        <div id="container">
            <div id="login-container" class="class-container">
                <h1>Please Log In</h1>
                <?php
try {
	$forgot = "";
	if (isset($_SESSION["error"])) {
		echo ('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
		unset($_SESSION["error"]);
		$_SESSION['forgot'] = 'on';
	}

	if (isset($_SESSION['forgot'])) {
		$forgot = "forgot <a href='forgot_password.php?forgot=username'>username</a> or <a href='forgot_password.php?forgot=pass'>password</a>";
	}
} //try
catch(Exception $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

catch(Error $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

?>

             <form method="post">
                 <p>Account: <input type="text" name="account" value="" class="form-control"></p>
                 <p>Password: <input type="password" name="pw" value="" class="form-control"></p>
                 <p>
                     <input type="submit" value="Log In" class="btn btn-info">
                     <a href="app.php" class='btn btn-info'>Cancel</a>
                 </p>
             </form>
<?php
echo $forgot;
?>
            </div>
        </div>
    </body>