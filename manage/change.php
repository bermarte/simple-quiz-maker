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

	// get url

	$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$page = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	$page.= ".php";
	$new_url = str_replace($page, "", $url);

	// $new_url is the url to be used

	include_once "../php_lib/pdo.inc.php";

	include '../PHPMailer-5.2-stable/PHPMailerAutoload.php';

	// partially taken from
	// https://www.dreamincode.net/forums/topic/370692-reset-password-system/
	// username reset

	if (isset($_POST["ForgotUsername"])) {

		// Harvest submitted e-mail address

		if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$email = $_POST["email"];
		}
		else {
			echo "email is not valid";
			echo "
        <br>
        <br>
        <a href=\"forgot_password.php?forgot=username\" class='btn btn-info'>Back</a>";
			exit;
		}

		try {

			// Check to see if a user exists with this e-mail

			$query = $pdo->prepare('SELECT email FROM simple_quiz.users WHERE email = :email');
			$query->bindParam(':email', $email);
			$query->execute();
			$userExists = $query->fetch(PDO::FETCH_ASSOC);

			// $conn = null;

		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		if ($userExists["email"]) {

			// Create a unique salt. This will never leave PHP unencrypted.

			$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

			// Create the unique user reset key

			$user = hash('sha512', $salt . $userExists["email"]);

			// $password = "abc";
			// Create a url which we will direct them to reset their usernames

			$pwrurl = "<a href= '" . $new_url . "reset_password.php?q=" . $user . "'>Reset</a>";
			$obj = "username reset";
			$txt = "Dear user,\n\nIf this e-mail does not apply to you please ignore it. It appears that you have requested a username reset\n\nTo reset your username, please click the link below. If you cannot click it, please paste it into your web browser's address bar.\n\n" . $pwrurl . "\n\nThanks,\nThe Administration";
			mail($userExists["email"], "www.yoursitehere.com - username Reset", $txt);

			// PHPmailer

			$mail = new PHPMailer;
			$mail->isSMTP(); // Set mailer to use SMTP
			$mail->Host = 'host'; // Specify main and backup SMTP servers
			$mail->SMTPAuth = true; // Enable SMTP authentication
			$mail->Username = 'username'; // SMTP username
			$mail->Password = 'password'; // SMTP password
			$mail->Port = 2525; // TCP port to connect to
			$mail->setFrom('php_script@localhost.com', 'phpscript');
			$mail->addAddress($email, $nm . ' ' . $fn); // Add a recipient
			$mail->isHTML(true);
			$mail->Subject = $obj;
			$mail->Body = $txt; //html can be used e.g. <b></b>
			$mail->AltBody = $txt;
			if (!$mail->send()) {

				// Message could not be sent

				echo "<br>Could not send mail.";

				// throw new Exception('Mail error.');
				// Mailer Error

				echo '<br>Mailer Error: ' . $mail->ErrorInfo;
			}
			else {

				// Message has been sent

				echo "Mail sent to $email.";
			}
		}
		else echo "<br>No user with that e-mail address exists.";
	}

	// password reset

	if (isset($_POST["ForgotPassword"])) {

		// Harvest submitted e-mail address

		if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$email = $_POST["email"];
		}
		else {
			echo "email is not valid";
			echo "
        <br>
        <br>
        <a href=\"forgot_password.php?forgot=pass\" class='btn btn-info'>Back</a>";
			exit;
		}

		try {

			// Check to see if a user exists with this e-mail

			$query = $pdo->prepare('SELECT email FROM simple_quiz.users WHERE email = :email');
			$query->bindParam(':email', $email);
			$query->execute();
			$userExists = $query->fetch(PDO::FETCH_ASSOC);

			// $conn = null;

		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		if ($userExists["email"]) {

			// Create a unique salt. This will never leave PHP unencrypted.

			$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

			// Create the unique user password reset key

			$password = hash('sha512', $salt . $userExists["email"]);

			// $password = "abc";
			// Create a url which we will direct them to reset their password

			$pwrurl = "<a href= '" . $new_url . "reset_password.php?q=" . $password . "'>Reset</a>";
			$obj = "password reset";
			$txt = "Dear user,\n\nIf this e-mail does not apply to you please ignore it. It appears that you have requested a password reset\n\nTo reset your password, please click the link below. If you cannot click it, please paste it into your web browser's address bar.\n\n" . $pwrurl . "\n\nThanks,\nThe Administration";
			mail($userExists["email"], "www.yoursitehere.com - Password Reset", $txt);

			// PHPmailer

			$mail = new PHPMailer;
			$mail->isSMTP(); // Set mailer to use SMTP
			$mail->Host = 'mail.smtp2go.com'; // Specify main and backup SMTP servers
			$mail->SMTPAuth = true; // Enable SMTP authentication
			$mail->Username = 'bermarte@hotmail.com'; // SMTP username
			$mail->Password = '0phY7cOPKRWJ'; // SMTP password
			$mail->Port = 2525; // TCP port to connect to
			$mail->setFrom('php_script@localhost.com', 'phpscript');
			$mail->addAddress($email, $nm . ' ' . $fn); // Add a recipient
			$mail->isHTML(true);
			$mail->Subject = $obj;
			$mail->Body = $txt; //html can be used e.g. <b></b>
			$mail->AltBody = $txt;
			if (!$mail->send()) {

				// Message could not be sent

				echo "<br>Could not send mail.";

				// throw new Exception('Mail error.');
				// Mailer Error

				echo '<br>Mailer Error: ' . $mail->ErrorInfo;
			}
			else {

				// Message has been sent

				echo "Mail sent to $email.";
			}
		}
		else echo "<br>No user with that e-mail address exists.";
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
                <br>
                <br>
                <a href="forgot_password.php?forgot=pass" class='btn btn-info'>Back</a>
        </div>
    </div>
</body>

</html>