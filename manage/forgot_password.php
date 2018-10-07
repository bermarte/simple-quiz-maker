<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
try{
        $out="";
        if ($_GET['forgot']=='pass'){
            $_SESSION["reset"]="pass";
            $out = "
            <h4>forgot password</h4>
            <form action=\"change.php\" method=\"POST\">
            <p>
            E-mail Address: <input type=\"text\" name=\"email\" size=\"20\" class=\"form-control\">
            </p>
            <p>
            <input type=\"submit\" name=\"ForgotPassword\" value=\"Request Reset\" class=\"btn btn-info\">
            <a href=\"login.php\" class='btn btn-info'>Cancel</a>
            </p>
            </form>";
            
        }
        if ($_GET['forgot']=='username'){
            $out = "
            <h4>forgot username</h4>
            <form action=\"change.php\" method=\"POST\">
            <p>E-mail Address: <input type=\"text\" name=\"email\" size=\"20\" class=\"form-control\">
            </p>
            <p>
            <input type=\"submit\" name=\"ForgotUsername\" value=\"Request Reset\" class=\"btn btn-info\">
            <a href=\"login.php\" class='btn btn-info'>Cancel</a>
            </p>
            </form>";
            $_SESSION["reset"]="username";
        }
        
        echo $out;
}//end try
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