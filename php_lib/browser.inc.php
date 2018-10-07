<?php
try{
    if (!isset($_SESSION['browser']) 
        && !$_SESSION['browser']==true){
        
        header( 'Location: ../index.php' ) ;
        return;  
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

