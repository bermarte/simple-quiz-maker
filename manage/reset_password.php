<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Simple Quiz-forgor password
    </title>
    <link rel="stylesheet" href="../css/warning.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
</head>

<body style="font-family: sans-serif;">
    <div id="container">
        <div id="container-app" class="class-container">
            <h1>Simple Quiz</h1>

            <?php
try {
	if (isset($_SESSION['reset']) && $_SESSION['reset'] == 'pass') {
		echo '
        <form action="reset.php" method="POST">
        <p>
        E-mail Address: <input type="text" name="email" size="20" class="form-control">
        </p>
        <p>
        New Password: <input type="password" name="password" size="20" class="form-control">
        </p>
        <p>
        Confirm Password: <input type="password" name="confirmpassword" size="20" class="form-control">
        </p>
        <input type="hidden" name="q" value="';
		if (isset($_GET["q"])) {
			echo $_GET["q"];
		}

		echo '"><input type="submit" name="ResetPasswordForm" value="Reset Password" class= "btn btn-info">
       </form>';
	}

	if (isset($_SESSION['reset']) && $_SESSION['reset'] == 'username') {
		echo '
        <form action="reset.php" method="POST">
        <p>
        E-mail Address: <input type="text" name="email" size="20" class="form-control">
        </p>
        <p>
        New Username: <input type="text" name="username" size="20" class="form-control">
        </p>
        <p>
        Confirm Username: <input type="text" name="confirmusername" size="20" class="form-control">
        </p>
        <input type="hidden" name="q" value="';
		if (isset($_GET["q"])) {
			echo $_GET["q"];
		}

		echo '">
        <input type="submit" name="ResetUsernameForm" value="Reset Username" class= "btn btn-info">
       </form>';
	}
} //end try
catch(Exception $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

catch(Error $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

?>
        </div>
    </div>
</body>

</html>