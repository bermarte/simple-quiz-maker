<?php
session_start();
include_once "../php_lib/pdo.inc.php";
include_once "../php_lib/browser.inc.php";
try{
    //session relative to the creation of file
    if (isset($_SESSION['stoprestartSwitch'])){
        unset($_SESSION['stoprestartSwitch']);
    }
    //for debug purposes
    //should be false
    $debug = false;   
    if ($debug || (isset($_SESSION['debug']) && $_SESSION['debug']=='true')){
        //reset FORM submit control for all the php files
        if (isset($_SESSION['form_submitted_create_quiz'])){
            unset($_SESSION['form_submitted_create_quiz']);
            //echo "already submitted";
        }
        if (isset($_SESSION['form_submitted_create_quiz_B'])){
            unset($_SESSION['form_submitted_create_quiz_B']);
            //echo "already submitted";
        }
        if (isset($_SESSION['form_submitted_create_quiz_C'])){
            unset($_SESSION['form_submitted_create_quiz_C']);
            //echo "already submitted";
        }
        if (isset($_SESSION['form_submitted_create_quiz_D'])){
            unset($_SESSION['form_submitted_create_quiz_D']);
            //echo "already submitted";
        }
        
        unset($_SESSION['debug']);
    }
    //from latest php unset session 'restart'
    if (isset($_SESSION['$restartSwitch'])){
        unset($_SESSION['$restartSwitch']);
    }
    
    if (isset($_POST['submit'])){
        
        //check if already submitted
        if (isset($_SESSION['form_submitted_create_quiz'])){
            header( 'Location: create_quiz_B.php' ) ;
            $_SESSION['error'] = 'Already submitted';
            return;  
        }
        
        //Required field names
        $required = array('nameDocument', 'nameCoach', 'MailCoach');
        
        // Loop over field names, make sure each one exists and is not empty
        $error = false;
        foreach($required as $field) {
            if (empty($_POST[$field])) {
                $error = true;
            }
        }
        if ($error) {
            //All fields are required";            
            $_SESSION["error"] = "All fields are required.";
            header( 'Location: create_quiz.php' ) ;
            return;  
        }
        //check mail
        if ( strpos($_POST['MailCoach'],'@') === false ) {
            $_SESSION['error'] = 'Bad format data';
            header( 'Location: create_quiz.php' ) ;
            return;  
        }
        //check length of input (how many characters)
        //document: 50
        //coach:50
        //mail: 320
        $stringLength = strlen($_POST['nameDocument']);
        if ($stringLength > 50){
            $error = true;
        }
        $stringLength = strlen($_POST['nameCoach']);
        if ($stringLength > 50){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "Maximum length is 50 characters.";
            header( 'Location: create_quiz.php' ) ;
            return;  
        }
        
        //end check mail
        $title = $_POST['nameDocument'];
        $coach = $_POST['nameCoach'];
        $mail = $_POST['MailCoach'];
        try{
            $sql = "INSERT INTO simple_quiz.document (title,coach,mail) VALUES (:title, :coach, :mail);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':title' => htmlentities($title),
                ':coach' => htmlentities($coach),
                ':mail' => htmlentities($mail)));
            
            //get last primary key added
            $lastId = $pdo->lastInsertId();
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        $_SESSION["document.id"] = $lastId;
        $_SESSION["document"] = $title;
        
        $_SESSION['success'] = 'Record Added';
        
        //check if form is submitted again
        $_SESSION['form_submitted_create_quiz'] = TRUE;
        header( 'Location: create_quiz_B.php' ) ;
        return;   
        
    }//if
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
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>
            Simple Quiz - Quiz maker
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/quiz-maker.css">
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
    </head>

    <body style="font-family: sans-serif;">
        <div id="container">
            <div id="quiz-maker-container" class="class-container">
                <h1>Create a quiz</h1>
                <?php
try{
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }
    //check submit in create_quiz_E.php
    if (isset($_SESSION['form_submitted'])){
        unset($_SESSION['form_submitted']);
    }
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
                    <form method="post">
                        <p>Name of the document: <input type="text" name="nameDocument" value="" class="form-control" placeholder="name of document" title="write here the name of your file"></p>
                        <p>Name of coach: <input type="text" name="nameCoach" value="" class="form-control" placeholder="coach" title="write your name"></p>
                        <p>Mail of coach: <input type="text" name="MailCoach" value="" class="form-control" placeholder="mail of coach" title="write your email"></p>
                        <p><input type="submit" value="Submit" class="btn btn-info" name="submit">
                            <a href="../manage/app.php" class="btn btn-info">Cancel</a></p>
                    </form>
            </div>
        </div>
    </body>