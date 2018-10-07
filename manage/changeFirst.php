<?php
session_start();

// fix Headers_already_sent warning

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>
            Simple Quiz - change data
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">

    <body style='font-family: sans-serif;'>
        <div id="container">
            <div id="container-data" class="class-container">
                <?php
include_once "../php_lib/pdo.inc.php";

try {
	if (isset($_SESSION['login']) && ($_SESSION['login'] == 'old' || $_SESSION['login'] == 'new')) {
		try {
			if (isset($_POST['submit'])) {

				// Required field names

				$required = array(
					'oldAccount',
					'newAccount',
					'ReNewAccount',
					'oldPass',
					'newPass',
					'ReNewPass'
				);

				// Loop over field names, make sure each one exists and is not empty

				$error = false;
				foreach($required as $field) {
					if (empty($_POST[$field])) {
						$error = true;
					}
				}

				if ($error) {

					// echo "<br />All fields are required";

					$_SESSION["error"] = "All fields are required.";
					header('Location: ChangeFirst.php');
					return;
				}

				// check length of inputs max.100

				$strLA = strlen($_POST['newAccount']);
				$strLNA = strlen($_POST['ReNewAccount']);
				$strLP = strlen($_POST['NewPass']);
				$strLNP = strlen($_POST['ReNewPass']);
				if ($strLA > 100 || $strLNA > 100 || $strLP > 100 || $strLNP > 100) {
					$error = true;
				}

				if ($error) {
					$_SESSION["error"] = "Maximum length is 100 characters.";
					header('Location: changeFirst.php');
					return;
				}

				// end check length

				else {
					$oldAccount = $_POST['oldAccount'];
					$newAccount = $_POST['newAccount'];
					$ReNewAccount = $_POST['ReNewAccount'];
					/*
					$oldPass = $_POST['oldPass'];
					$newPass = $_POST['newPass'];
					$ReNewPass = $_POST['ReNewPass'];
					*/
					$oldPass = md5($_POST['oldPass']);
					$newPass = md5($_POST['newPass']);
					$ReNewPass = md5($_POST['ReNewPass']);

					// $oldPwddb = md5($oldPwddb);

					/*

					// convert password to hashes

					$sql = "UPDATE users SET password=? WHERE id=?";
					$pdo->prepare($sql)->execute([md5('password'), 1]);
					*/

					// retrieve data

					$sqlC = "SELECT * FROM users";
					$sql = $pdo->prepare($sqlC);
					$sql->execute();
					$result = $sql->fetchAll(PDO::FETCH_ASSOC);
					/*
					echo print_r($result) gives an array of array
					Array ( [0] => Array ( [id] => 1 [username] => admin [password] => password ) ) 1
					*/
					$oldUserdb = $result[0]['username'];
					$oldPwddb = $result[0]['password'];
					/*
					two steps: first check account than password
					*/

					// check old account

					if ($oldAccount == $oldUserdb) {

						// echo "<br />username does match";
						// check new user

						if ($newAccount == $ReNewAccount) {
							if ($newAccount == $oldUserdb) {

								// echo "New account is the same as the old account.";

								$_SESSION["error2"] = "New account is the same as the old account.";
							}
							else {

								// ok now change the user account

								$sql = "UPDATE users SET username=? WHERE id=?";
								$pdo->prepare($sql)->execute([$newAccount, 1]);
								$_SESSION["success"] = "Username changed.";
								header('Location: ChangeFirst.php');

								// return;

							}
						}
						else {

							// echo "<br />new usernames don't match";

							$_SESSION["error2"] = "New usernames are not the same.";

							// header( 'Location: ChangeFirst.php' ) ;
							// return;

						}
					}
					else {

						// echo "<br />username does not match";

						$_SESSION["error"] = "Username does not match.";
						header('Location: ChangeFirst.php');
						return; 
					}

					// second part
					// check old password

					if ($oldPass == $oldPwddb) {

						// echo "<br />password does match";

						if ($newPass == $ReNewPass) {
							if ($newPass == $oldPwddb) {
								if (isset($_SESSION["error2"])) {
									$str = str_replace(".", "", $_SESSION["error2"]);
									$_SESSION["error2"] = $str . " and New Password is the same as the old one.";
								}
								else {
									$_SESSION["error2"] = "New Password is the same as the old one";
								}
							} //$newPass == $oldPwddb
							else {

								// ok change the password

								$sql = "UPDATE users SET password=? WHERE id=?";
								$pdo->prepare($sql)->execute([$newPass, 1]);
								if (isset($_SESSION["success"])) {

									// remove point from previous session
									// if concateneting two sessions
									// if session exists

									$str = str_replace(".", "", $_SESSION["success"]);
									$_SESSION["success"] = $str . " and Password changed.";
								}
								else {
									$_SESSION["success"] = "Password changed";
								}
							}

							header('Location: ChangeFirst.php');
							return;
						} //newPass == $ReNewPass
						else {

							// not $oldPass == $oldPwddb

							if (isset($_SESSION["error2"])) {

								// remove point from previous session
								// if concateneting two sessions
								// if session exists

								$str = str_replace(".", "", $_SESSION["error2"]);
								$_SESSION["error2"] = $str . " and New passwords are not the same.";
							}
							else {
								$_SESSION["error2"] = "New passwords are not the same.";
							}

							header('Location: ChangeFirst.php');
							return;
						}

						if (isset($_SESSION["error"])) {

							// remove point from previous session
							// if concateneting two sessions
							// if session exists

							$str = str_replace(".", "", $_SESSION["error"]);
							$_SESSION["error"] = $str . " and Password does not match.";
						}
						else {


							$_SESSION["error"] = "new password: " . $newPass . "<br />" . "old password from db: " . $oldPwddb . "<br />" . "old password: " . $oldPass . "<br />" . "Password does not match";
						}

						header('Location: ChangeFirst.php');
						return;
					}
				} //$error = false;
			}

			// NOT isset($_SESSION['login'])&& $_SESSION['login'] =='old'

			else {

				// do nothing

				;
			}
		} //end try
		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		} //end catch
	}
	else {

		// echo "<p>you must be logged in to change your password.</p>";

		$_SESSION["error"] = "You must be logged in to change your password.";
		header('Location: ChangeFirst.php');
		return;
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
                    <h1>Change username or password</h1>
                    <?php
try {
	if (isset($_SESSION["error"])) {
		echo ('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
		unset($_SESSION["error"]);
	}

	if (isset($_SESSION["error2"])) {
		echo ('<p style="color:red">' . $_SESSION["error2"] . "</p>\n");
		unset($_SESSION["error2"]);
	}

	if (isset($_SESSION["success"])) {
		echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
		unset($_SESSION["success"]);
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
                        <form method='post'>
                            Old Account: <input type='text' name='oldAccount' value='' class="form-control" placeholder="Old account"> New Account: <input type='text' name='newAccount' value='' class="form-control" class="form-control" placeholder="New Account"> Repeat New Account: <input type='text' name='ReNewAccount' value='' class="form-control" placeholder="Repeat new account"> Old Password: <input type='password' name='oldPass' value='' class="form-control" placeholder="Old Password"> New Password: <input type='password' name='newPass' value='' class="form-control" placeholder="New Password"> Repeat New Password: <input type='password' name='ReNewPass' value='' class="form-control" placeholder="Repeat New Password">
                            <br>
                            <p>
                                <input type='submit' value='Change data' name='submit' class="btn btn-info">
                                <a href="app.php?array();" class="btn btn-info">Back</a>
                            </p>
                        </form>

            </div>
        </div>
    </body>