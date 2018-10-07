<?php
session_start();
include_once "../php_lib/pdo.inc.php";
include_once "../php_lib/browser.inc.php";
include_once "../php_lib/abort.php";
try{
    //for debug purposes
    $debug = false;
    
    if ($debug){
        if (isset($_SESSION['form_submitted_create_quiz_B'])){
            unset($_SESSION['form_submitted_create_quiz_B']);
            //echo "already submitted";
        }
    }
    
    if (isset($_POST['submit'])){
        
        //check if already submitted
        if (isset($_SESSION['form_submitted_create_quiz_B'])){
            header( 'Location: create_quiz_C.php' ) ;
            $_SESSION['error'] = 'Already submitted';
            return;  
        }
        
        // Required field names
        $required = array('nameGroup', 'howMany');
        
        // Loop over field names, make sure each one exists and is not empty
        $error = false;
        foreach($required as $field) {
            if (empty($_POST[$field])) {
                $error = true;
            }
        }
        if ($error) {
            //echo "<br>All fields are required";            
            $_SESSION["error"] = "All fields are required.";
            header( 'Location: create_quiz_B.php' ) ;
            return;  
        }
        
        //check data type
        if (!ctype_digit($_POST['howMany'])){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "Integers are required.";
            header( 'Location: create_quiz_B.php' ) ;
            return;  
        }
        
        //there are max 50 questions per document
        if((int)$_POST['howMany']>50){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "You can have maximum 50 questions for a single document.";
            header( 'Location: create_quiz_B.php' ) ;
            return;  
        }
        //check length of input
        $stringLength = strlen($_POST['nameGroup']);
        if ($stringLength > 50){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "Maximum length is 50 characters.";
            header( 'Location: create_quiz_B.php' ) ;
            return;  
        }
        
        //html form:
        //nameGroup and howMany fields
        $group = $_POST['nameGroup'];
        $howMany = $_POST['howMany'];
        //db items:
        //group.category
        //group.numQuestions
        //group.id_document FK > $_SESSION['document.id'];
        try{
            $sql = "INSERT INTO simple_quiz.group (category, numQuestions,id_document) VALUES (:category, :numQuestions,    :id_document);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':category' => $group,
                ':numQuestions' => $howMany,
                'id_document' => $_SESSION['document.id']));
            //get latest id added
            $lastId = $pdo->lastInsertId(); 
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        $_SESSION["group.id"] = $lastId;
        //get number of questions
        $_SESSION["group.numQuestions"] = $howMany;
        
        $_SESSION['success'] = 'Group added';
        //check if form is submitted again
        $_SESSION['form_submitted_create_quiz_B'] = TRUE;
        header( 'Location: create_quiz_C.php' ) ;
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
<p>Add a group of questions: <input type="text" name="nameGroup" value="" class="form-control" placeholder="Name of group" title = "write your category/group"></p>
<p>How many questions has this specific group: <input type="text" name="howMany" value="" class="form-control" placeholder="How many questions" title = "write how many questions you want"></p>
<p><input type="submit" value="Submit" class="btn btn-info" name="submit">
<input type="submit" value="Abort" class="btn btn-info" name="abort" title = "erase entry and go back to the Homepage"></p>
</form>
</div>
</div>
</body>
