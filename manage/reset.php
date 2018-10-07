<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Simple Quiz-forgor password
    </title>
    <!-- stop re-submit -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
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
	include_once "../php_lib/pdo.inc.php";

	// reset password

	if (isset($_POST["ResetPasswordForm"])) {

		// Gather the post data

		$email = $_POST["email"];
		$password = $_POST["password"];
		$confirmpassword = $_POST["confirmpassword"];
		$hash = $_POST["q"];

		// Use the same salt from the forgot_password.php file

		$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

		// Generate the reset key

		$resetkey = hash('sha512', $salt . $email);

		// Does the new reset key match the old one?

		if ($resetkey == $hash) {
			if ($password == $confirmpassword) {

				// hash password
				// $password = hash('sha512', $salt.$password);

				$password = md5($password);

				// Update the user's password

				try {
					$query = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');
					$query->bindParam(':password', $password);
					$query->bindParam(':email', $email);
					$query->execute();
					$conn = null;
				}

				catch(PDOexception $e) {
					include ("../php_lib/myExceptionHandling.inc.php");

					echo myExceptionHandling($e, "../logs/error_log.csv");
				}

				echo "Your password has been successfully reset.";
			}
			else echo "Your password's do not match.";
		}
		else echo "Your password reset key is invalid.";
	}

	// reset username

	if (isset($_POST["ResetUsernameForm"])) {

		// Gather the post data

		$email = $_POST["email"];
		$username = $_POST["username"];
		$confirmusername = $_POST["confirmusername"];
		$hash = $_POST["q"];

		// Use the same salt from the forgot_password.php file

		$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

		// Generate the reset key

		$resetkey = hash('sha512', $salt . $email);

		// Does the new reset key match the old one?

		if ($resetkey == $hash) {
			if ($username == $confirmusername) {

				// Update username

				try {
					$query = $pdo->prepare('UPDATE users SET username = :username WHERE email = :email');
					$query->bindParam(':username', $username);
					$query->bindParam(':email', $email);
					$query->execute();
					$conn = null;
				}

				catch(PDOexception $e) {
					include ("../php_lib/myExceptionHandling.inc.php");

					echo myExceptionHandling($e, "../logs/error_log.csv");
				}

				echo "Your username has been successfully reset.";
			}
			else echo "Your username's do not match.";
		}
		else echo "Your username reset key is invalid.";
	}
}

catch(Exception $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

catch(Error $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

?>
                <br><br>
                <a href="login.php" class='btn btn-info'>Back to Login</a>
        </div>
    </div>
</body>

</html>