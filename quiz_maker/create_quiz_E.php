<?php
session_start();
include_once "../php_lib/pdo.inc.php";
include_once "../php_lib/compile.inc.php";
include_once "../php_lib/abort.php";
//fix Headers_already_sent warning
ob_start();
try{
    $restart = "";
    $home ="";
    $submitInput = "<input type=\"submit\"value=\"Submit\" class=\"btn btn-info\" name=\"submit\">";
    if ($_SESSION['form_submitted']){
        $submitInput ="";
        $home= "<a href=\"../manage/app.php\" class=\"btn btn-info\">Home</a>";
    }
    
    $end;
    //restart
    
    if (isset($_SESSION['$restartSwitch'])){
        //this doesn't fire
        $restart = "
        <form>
        <label title= \"create quiz file on your hard disk\">
        <input type = \"submit\" name=\"compile\" value=\" Create file\" class=\"btn btn-info\" method=\"post\">
        </form>
        ";
        unset($_SESSION['$restartSwitch']);  
    }
    
    else{
        $restart = "";
    }
    
    if (isset($_SESSION['stoprestartSwitch'])){
        $restart = "";
        $uri = $_SERVER['REQUEST_URI'];
        $link = str_replace('quiz_maker/create_quiz_E.php', 'quiz', $uri); 
        $localLink = 'http://' . $_SERVER['HTTP_HOST'] . $link."/".$_SESSION['folder'];
        //check if localhost
        //use absolute path
        if($_SERVER['SERVER_NAME']='localhost'){
            $end ='File created in '. "<label title='here you can find the file created'>
            <a href='".$localLink."' target='_blank' class='file_link'><b>".$_SESSION['path']."</b></a></label>";
        }
        //remote use URL
        else{
            $end ='File created in '. "<label title='here you can find the file created'>
            <a href='".$localLink."' target='_blank' class='file_link'><b>".$localLink."</b></a></label>";
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
        <link rel="stylesheet" href="../css/question-container.css" rel="stylesheet">
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/slider.css" rel="stylesheet">
    </head>

    <body style="font-family: sans-serif;">
        <script src="../js/updateTextInput.js">
        </script>
        <!-- text for radio buttons -->
        <div id="container">
            <div id="questions-container" class="class-container">
                <?php
 try{   
     /*
     var_dump($_SESSION['latestDocumentId']);
     then id's of the choices that are right
     var_dump($_SESSION['Booleans']);
     */      
     
     if ( isset($_SESSION["error"]) ) {
         echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
         unset($_SESSION["error"]);
     }
     if ( isset($_SESSION["success"]) ) {
         echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
         unset($_SESSION["success"]);
         unset($_POST['submit']);
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
    if (isset($_SESSION["goNext"])){
        $execute = true;
    }
    if ($execute){
        $Nquestions = $_SESSION["Nquestions"];
        
        echo 
            "
            <h1>Create a quiz (settings)</h1>
            <form method=\"post\">
            
            You have <b>$Nquestions</b> question(s).
            <!-- input randomness (choices) -->
            Do you want to randomly display questions and answers?
            <br>
            <!-- yes no part -->
            <div class=\"centered\">
            
            <div class=\"options yes-no\">
            YES<br>
            NO
            </div>
            
            <div class='options'>
            <!-- input -->
            <label title= \"select if you want them to be shown randomly\">
            <input type = \"radio\" name=\"isRandomChoices\" id=\"isRandomChoices\" value=\"1\" class=\"form-control\">
            <img>
            </label>
            <br>
            
            <!-- input -->
            <label title= \"select if you don't want them to be shown randomly (defaut)\">
            <input type = \"radio\" name=\"isRandomChoices\" id=\"isRandomChoices\" value=\"0\" class=\"form-control\" checked>
            <img>
            </label>
            </div><!-- input-group -->
            
            </div>
            <br>
            <!-- end yes no part -->
            
            <!-- score -->
            <div id=\"which\">
            Which score (in percentage) is needed to pass the quiz?
            <input type = \"text\" id=\"percentageInputDiv\" name=\"percentageInputDiv\" maxlength=\"4\" size=\"4\" value =\"50\" readonly>
            </div>
            <br>
            <!-- input percentage -->
            
            
            
            <div id=\"slider-cntnr\">
            <input type=\"range\" id=\"frame-slider\" oninput=\"updateFollowerValue(this.value)\" class=\"slider\">
            <div id=\"slider-follow-cntnr\">
            <div id=\"slider-follow\">
            <div id=\"slider-val-cntnr\">
            <!--<span id=\"slider-val\"></span>-->
            <output for=\"frame-slider\" onforminput=\"value = percentageInput.valueAsNumber;\" id=\"slider-val\"></output>
            </div>
            </div>
            </div>
            </div>
            <span style=\"color: green;\" class=\"span\">$end</span>
            <br>
            $submitInput
            <input type=\"submit\" value=\"Abort\" class=\"btn btn-info\" name=\"abortd\" title = \"erase entry and go back to the Homepage\">
            
            $home
            <!-- <a href=\"../manage/app.php\" class=\"btn btn-info\">Home</a>-->
            $restart
            </form>
            ";
    }
    //check submit
    $isSubmit = false;
    if (isset($_POST['submit'])){
        $isSubmit = true;
    }
    if ($isSubmit){
        
        if (isset($_SESSION['form_submitted'])){
            header( 'Location: create_quiz_E.php' ) ;
            $_SESSION['error'] = 'Settings already submitted';
            return;  
        }
        if ( isset($_SESSION["success"]) ) {
            echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
            unset($_SESSION["success"]);
        }
        
        //first part
        //$_SESSION["document"] is the title of the document
        $document = $_SESSION["document"];
        echo $_POST["isRandomChoices"];
        //insert boolean randomness into db
        try{
            $sql = "INSERT INTO simple_quiz.randomness (isRandom)
            VALUES (:isRandom);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':isRandom'=>$_POST["isRandomChoices"], 
            ));
        }//end try
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        try{
            //update foreign key of randomness in 2 steps
            //first get document title from document
            $sql = "SELECT id FROM
            document 
            WHERE document.title = :document_title;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':document_title'=>$document
            ));
            $fk = $stmt->fetchAll();
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        foreach ($fk as $row) {
            $_SESSION['fk'] = $row[0];
        }
        //then update FK in randomness
        try{
            $sql = "UPDATE randomness
            SET randomness.id_titleR = :fk
            WHERE randomness.id = :fk ;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':fk'=>$_SESSION['fk']
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        
        //second part
        //update percentage
        try{
            $sql = "INSERT INTO simple_quiz.percentage (percent)
            VALUES (:percent);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':percent'=>$_POST["percentageInputDiv"] 
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        //update foreign key of percentage
        //from SESSION
        //then update FK in percentage
        try{
            $sql = "UPDATE percentage
            SET percentage.id_titleP = :fk
            WHERE percentage.id = :fk ;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':fk'=>$_SESSION['fk']
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        
        $_SESSION['success'] = 'Record Added';
        $_SESSION['$restartSwitch'] = TRUE;
        $_SESSION['form_submitted'] = TRUE;
        //unset Session of create_quiz.php
        if (isset(_SESSION['form_submitted_create_quiz'])){
            unset($_SESSION['form_submitted_create_quiz']);
        }
        //erase SESSION if starting again from create_quiz.php
        $_SESSION['debug'] = TRUE;
        header( 'Location: create_quiz_E.php' );
        //TODO : change location? close quiz
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

                        <script src="../js/slider.js">
                        </script>

                        <script src="../js/bubble.js">
                        </script>
            </div>
        </div>
    </body>