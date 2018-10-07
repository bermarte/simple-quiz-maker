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
    //set to true to debug
    $debug = false;   
    if ($debug){
        if (isset($_SESSION['form_submitted_create_quiz_D'])){
            unset($_SESSION['form_submitted_create_quiz_D']);
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
        <link rel="stylesheet" href="../css/question-container_create-quiz_D.css">
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
    $execute = false;
    if (isset($_SESSION["questions.numQUestions"])){
        $Nquestions = $_SESSION["questions.numQUestions"];
        for ($k=0; $k<$Nquestions;$k++){
            if (isset($_SESSION["questions.id_".$k])){
                //I use this variable 
                //to move this part out of
                //the for loop
                $execute = true;
            }
        }
    }
    if ($execute){
        /* start array */
        //check total number of choices
        //and make an array
        //ex. 3 became {1,2,3}
        if (isset($_SESSION["choices.total"])){
            $total = $_SESSION["choices.total"];
            $arrChoices = array();
            for ($i=0; $i<=$total; $i++){
                array_push($arrChoices,$i); 
            }
        }
        //reverse becaus pop starts from last element
        $arrChoicesReverse = array_reverse($arrChoices);
        /*
        suppose you have 5 possible choices in total
        print_r($arrChoices);
        // gives
        //Array ( [0] => 0 [1] => 1 [2] => 2 [3] => 3 [4] => 4 [5] => 5 ) 
        echo $arrChoices[5];
        //gives 5
        */  
        
        //get sessions's values that are like 
        //echo $_SESSION['questions.id_0_text']
        //echo $_SESSION['choices.id_0_number']
        echo "<form method=\"post\">";
        for ($m=1; $m<= $Nquestions;$m++){
            $times = $m;
            $nSessions = $m-1;
            
            echo "
            <p>
            <h4 class = \"Choices\">
            Choices for question n. ".$times."
            </h4>".
                "<h5 class = \"toLeft\" title = \"this is your question\">
                Question:
                </h5>
                <p class=\"pText\">
                <b>".
                htmlentities($_SESSION['questions.id_'.$nSessions.'_text']).
                "</b>
                </p>";
            
            //"session is: ".$_SESSION['choices.id_'.$nSessions.'_number'];
            $nChoices = $_SESSION['choices.id_'.$nSessions.'_number'];
            //continue with choices fields
            //create form for choices
            //echo "<form method=\"post\">"; bermarte
            for ($j = 1; $j<= $nChoices; $j++){
                // pop $arrChoicesReverse
                $element = array_pop($arrChoicesReverse);
                //arrays start with 0
                //we don't want 0 as id
                $elementId = $element+1;
                echo 
                    "
                    <!-- choice -->
                    
                    <label for \"question_choice_".$elementId."\" class=\"labels\">Choice's text</label>
                    <div class='input-group'>
                    <input type = \"text\" value = \"\"
                    class = \"form-control\" placeholder = \"Write your choice\" id =\"question_choice_".$elementId."\" name = \"question_choice_".$elementId."\" title = \"write your choice's text here\">
                    
                    <!-- hidden field -->
                    
                    <input type=\"hidden\" name=\"id_questions_".$elementId."\" id=\"id_questions_".$elementId."\" value=\"".$times."\">
                    
                    
                    <!-- radio -->
                    
                    <div class= \"options\">
                    <label title= \"select if true\">
                    <input type=\"radio\"name=\"radio[".$times."]\" value=\"".$elementId."\" id = \"radio_".$elementId."\"> 
                    <!--TRUE-->
                    <img>
                    </label>
                    </div>
                    </div>
                    </p>";
            }
            
        }
        echo "<p><input type=\"submit\"value=\"Submit\" class=\"btn btn-info\" name=\"submit\">
        <input type=\"submit\" value=\"Abort\" class=\"btn btn-info\" name=\"abortc\" title = \"erase entry and go back to the Homepage\">";
        echo "</form>";
    }
    //check submit
    $isSubmit = false;
    if (isset($_POST['submit'])){ 
        $isSubmit = true;
    }
    
    if ($isSubmit){
        
        //check if already submitted
        if (isset($_SESSION['form_submitted_create_quiz_D'])){
            header( 'Location: create_quiz_E.php' ) ;
            $_SESSION['error'] = 'Already submitted';
            return;  
        }
        
        //check if fields are empty
        $error = false;
        for ($n=1;$n<=$_SESSION["choices.total"];$n++){
            if (empty($_POST["question_choice_$n"])){
                $error = true;
            }  
        }
        
        if ($error) {           
            $_SESSION["error"] = "All fields are required.";
            header( 'Location: create_quiz_D.php' ) ;
            return;  
        }
        
        //check if no radioboxes are selected
        $myboxes = $_POST['radio'];
        if(empty($myboxes)){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "You didn't select any boxes.";
            header( 'Location: create_quiz_D.php' ) ;
            return;  
        }
        //check radioboxes
        if (count($myboxes) != $Nquestions){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "All boxes are required.";
            header( 'Location: create_quiz_D.php' ) ;
            return;  
        }
        //check maximum length
        //for check question_choice_n
        for ($n=1;$n<=$_SESSION["choices.total"];$n++){
            $stringLength = strlen($_POST["question_choice_$n"]);
            if ($stringLength > 600){
                $error = true;
            }
        }
        if ($error) {           
            $_SESSION["error"] = "Maximum length is 600 characters.";
            header( 'Location: create_quiz_D.php' ) ;
            return;  
        }
        //check if there are doubles in inputs
        $arrInputs =array();
        for ($x=1; $x<= $_SESSION["choices.total"]; $x++){
            $answersTxt = "question_choice_".$x;
            $input = $_POST[$answersTxt];
            array_push($arrInputs,$input);
        }
        //check doubles
        if(count($arrInputs) != count(array_unique($arrInputs))){
            $error = true;
        }
        if ($error) {           
            $_SESSION["error"] = "Answers should be unique.";
            header( 'Location: create_quiz_D.php' ) ;
            return;  
        }
        
        //count first how many id's there are
        //check which choice is right
        //if there are already rows add them
        //to upgrade isRight columns
        try{
            $sql = "SELECT COUNT(choices.id)
            FROM simple_quiz.choices;";
            $result = $pdo->prepare($sql); 
            $result->execute(); 
            $Nrows = $result->fetchColumn(); 
            
            //get first the latest document.id
            $sql = "SELECT max(choices.id_questions)
            FROM simple_quiz.choices;";
            $result = $pdo->prepare($sql); 
            $result->execute(); 
            $latestDocumentId = $result->fetchColumn();
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");  
        }
        
        //for cycle db insert using total
        for ($w=1;$w<=$total; $w++){
            try{
                $sql = "INSERT INTO simple_quiz.choices (answer, id_questions)
                VALUES (:answer, :id_questions);";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':answer'=>$_POST["question_choice_".$w],
                    ':id_questions'=>(int)$_POST["id_questions_".$w]+(int)$latestDocumentId
                    //':id_questions'=>$_POST["id_questions_".$w
                ));
            }
            catch (PDOexception $e){
                include("../php_lib/myExceptionHandling.inc.php");
                echo myExceptionHandling($e,"../logs/error_log.csv");
            }
            
            //check for isRight in compile.inc.php
            //get id_questions
            $_SESSION['latestDocumentId'] .= (int)$_POST["id_questions_".$w]+(int)$latestDocumentId.",";
            
            //dump($latestDocumentId);
        }
        
        foreach($_POST['radio'] as $option_num => $option_val){
            
            //update field
            try{
                $sql = "UPDATE simple_quiz.choices
                SET isRight = :myBool
                WHERE id = :myVal";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':myBool'=>"1",
                    ':myVal'=>(int)$option_val+(int)$Nrows
                ));
            }
            catch (PDOexception $e){
                include("../php_lib/myExceptionHandling.inc.php");
                echo myExceptionHandling($e,"../logs/error_log.csv");
            }
            //check for isRight in compile.inc.php
            //get isRight values
            //get the id for the ones that are right
            $_SESSION['Booleans'] .= (int)$option_val+(int)$Nrows.",";
        }
        $_SESSION["goNext"] = "go next";
        $_SESSION['success'] = 'Records for the answers Added';
        //check if form is submitted again
        $_SESSION['form_submitted_create_quiz_D'] = TRUE;
        //how many questions for next php file
        $_SESSION["Nquestions"] = $Nquestions;
        header("Location: create_quiz_E.php");
        
    } 
    echo 
        "</div>
        </div>
        </body>";
}//end try
catch(Exception $e) {
        include ("../php_lib/myExceptionHandling.inc.php");
        
        echo myExceptionHandling($e, "../logs/error_log.csv");
}
    
catch(Error $e) {
        include ("../php_lib/myExceptionHandling.inc.php");
        
        echo myExceptionHandling($e, "../logs/error_log.csv");
}