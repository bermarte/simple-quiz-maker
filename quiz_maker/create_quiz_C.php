<?php
session_start();
include_once "../php_lib/pdo.inc.php";
include_once "../php_lib/browser.inc.php";
include_once "../php_lib/abort.php";
//fix Headers_already_sent warning
ob_start();
try{
    //for debug purposes
    //should be false
    $debug = false;   
    if ($debug){
        if (isset($_SESSION['form_submitted_create_quiz_C'])){
            unset($_SESSION['form_submitted_create_quiz_C']);
            //echo "already submitted";
        }
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

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>
            Simple Quiz - Quiz maker
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/question-container.css">
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
    </head>

    <body style="font-family: sans-serif;">
        <div id="container">
            <div id="questions-container" class="class-container">
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

<?php
try{
        if (isset($_SESSION["group.id"])&& isset($_SESSION["group.numQuestions"])){
            echo "<form method=\"post\">";
            $questions = $_SESSION["group.numQuestions"];
            $arrQuestions = []; 
            $arrNumChoices = [];
            for ($x=1; $x<=$questions; $x++){
                $times = $x;
                //questions
                $out = "
                <p>
                <h4 class = \"questions-text\">
                Question ".$times."
                </h4>
                <!-- question -->
                <label for \"question_".$times."\" class=\"labels\">Text of question</label>
                <input type = \"text\" name = \"question_".$times."\" value = \"\" class = \"form-control\" placeholder = \"Write here your question\" id = \"question_".$times."\" title = \"write the text of your question\">
                <!-- n. choices -->
                <label for \"numChoices_".$times."\" class=\"labels\">How many choices</label>
                <input type = \"text\" name = \"numChoices_".$times."\" value = \"\" class = \"form-control\" placeholder = \"how many choices\" id = \"numChoices_".$times."\" title = \"write a number\">
                </p>";
                echo $out;
                $arrQuestions[] .= "question_".$times;
                $arrNumChoices[] .= "numChoices_".$times;
            }
            echo "<p>
            <input type='submit' value='Submit' class='btn btn-info' name='submit'>
            <input type='submit' value='Abort' class='btn btn-info' name='abortb' title = 'erase entry and go back to the Homepage'>";
            echo "</form>";
        }
        //check submit
        if (isset($_POST['submit'])){
            //check if already submitted
            if (isset($_SESSION['form_submitted_create_quiz_C'])){
                header( 'Location: create_quiz_D.php' ) ;
                $_SESSION['error'] = 'Already submitted';
                return;  
            }
            //merge arrays
            $arr = array_merge($arrQuestions,$arrNumChoices);
            
            // Loop over field names, make sure each one exists and is not empty
            $error = false;
            foreach($arr as $field) {
                if (empty($_POST[$field])) {
                    $error = true;
                }
            }
            if ($error) {           
                $_SESSION["error"] = "All fields are required.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            //we want only integers for choices
            foreach($arrNumChoices as $field) {
                if (!ctype_digit($_POST[$field])){
                    $error = true;
                }
            }
            if ($error) {           
                $_SESSION["error"] = "Integers are required.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            //check that choises are minimum two
            foreach($arrNumChoices as $field) {
                if ((int)($_POST[$field])< 2) {
                    $error = true;
                }
            }
            if ($error) {           
                $_SESSION["error"] = "You need minimum 2 choices.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            foreach($arrNumChoices as $field) {
                if ((int)($_POST[$field])> 25) {
                    $error = true;
                }
            }
            if ($error) {           
                $_SESSION["error"] = "You can have maximum 25 answers for a single question.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            //check length of input
            for ($x=1; $x<=$questions; $x++){
                $questionsTxt = "question_".$x;
                $stringLength = strlen($_POST[$questionsTxt]);
                if ($stringLength > 600){
                    $error = true;
                }
            }
            if ($error) {           
                $_SESSION["error"] = "Maximum length is 600 characters.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            
            //check if there are doubles in inputs
            $arrInputs =array();
            for ($x=1; $x<= $questions; $x++){
                $questionsTxt = "question_".$x;
                $input = $_POST[$questionsTxt];
                array_push($arrInputs,$input);
            }
            //check doubles
            if(count($arrInputs) != count(array_unique($arrInputs))){
                $error = true;
            }
            if ($error) {           
                $_SESSION["error"] = "Questions should be unique.";
                header( 'Location: create_quiz_C.php' ) ;
                return;  
            }
            
            
            $sum = 0; 
            //insert data in db
            $count = count($arrNumChoices);
            for ($i=0;$i<$count;$i++){
                //check it with 
                //echo  $_POST[$arrNumChoices[$i]];
                try{
                    $sql = "INSERT INTO simple_quiz.questions (question, numAnswers, id_group)
                    VALUES (:question, :numAnswers, :id_group);";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                        ':question' => htmlentities($_POST[$arrQuestions[$i]]),
                        ':numAnswers' => $_POST[$arrNumChoices[$i]],
                        'id_group' => $_SESSION["group.id"]));
                }
                catch (PDOexception $e){
                    include("../php_lib/myExceptionHandling.inc.php");
                    echo myExceptionHandling($e,"../logs/error_log.csv");
                }
                //populate SESSION id_questions for next php file
                //array starts with O we add 1 to the SESSION
                $_SESSION['questions.id_'.$i] = $i;
                //check values with
                //echo $_SESSION['questions.id_'.$i]."<br>";
                //add question's texts to SESSIONS
                //for next php file
                $_SESSION['questions.id_'.$i.'_text'] = $_POST[$arrQuestions[$i]];
                $_SESSION['choices.id_'.$i.'_number'] = $_POST[$arrNumChoices[$i]];
                
                /*
                $lastIds = $pdo->lastInsertId();
                $_SESSION['lastIds'] += $lastIds;
                */
                
                
                //gives numbers
                //count choices
                $sum += intval($_POST[$arrNumChoices[$i]]); 
            }
            //put choices's amount in a session
            $_SESSION["choices.total"] = $sum;
            
            $_SESSION["questions.numQUestions"] = $count;
            $_SESSION['success'] = 'Questions added';
            //check if form is submitted again
            $_SESSION['form_submitted_create_quiz_C'] = TRUE;
            
            header( 'Location: create_quiz_D.php' ) ;
            return;
            
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
            </div>
        </div>
    </body>