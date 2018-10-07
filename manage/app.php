<?php
session_start();
include_once "../php_lib/pdo.inc.php";
//include_once "../php_lib/erase.inc.php";
ob_start();
try{
    //session relative to the creation of file
    if (isset($_SESSION['stoprestartSwitch'])){
        unset($_SESSION['stoprestartSwitch']);
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
            Simple Quiz
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
try{
    
    /*
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }
    */
   
    if ( isset($_SESSION["warning"]) ) {
        echo $_SESSION["warning"]."\n";
        unset($_SESSION["warning"]);
    }

  
    if (isset($_SESSION["account"]) && isset($_SESSION["password"]) && isset($_GET['checkWarning'])){
        
        echo($_POST['checkWarning']);
        //default username and password
        if($_SESSION["account"]=="admin" || $_SESSION["password"] == md5("password")){
            $_SESSION['login'] = 'old';
            $UserOrPass ="";
            
            if ($_SESSION["account"]=="admin" && $_SESSION["password"] != md5("password")){
                $_SESSION['UserOrPass'] = "account";
            }
            elseif($_SESSION["account"]!="admin" && $_SESSION["password"] == md5("password")){
                $_SESSION['UserOrPass'] = "password";
            }
            else{
                $_SESSION['UserOrPass'] = "account or password";
            }
             
                //GET avoids infinite loop
                
                $_SESSION["warning"] =
                '<p style=color:green>Logged in.</p>'."\n".
                '<div class = "warning">'.
                '<p style="color:red" id ="warning-text"><b>Warning:</b></p>'.
                '<p>Please consider to change default '. $_SESSION['UserOrPass'] .'.</p>'.
                '</div>';
                header('Location: app.php');
                return;
            
        }
        else{
                        
                $_SESSION['login'] = 'new';
                $_SESSION["warning"] = 
                '<p style=color:green>Logged in.</p>'."\n".
                header('Location: app.php');
                return;
          
        }
         
    }
    // Check if we are logged in
    if ( ! isset($_SESSION["account"]) ) { ?>
                <p>Please <a href="login.php">Log In</a> to start.</p>
                <?php } else { 
       ?>

                <p class="presentation">
                    Here you can build a very crude multiple choice type quiz. The coach can create a number of questions and provide multiple answers, of which only one being true. At the end he can provide a percentage necessary to pass the test; a file to be uploaded to a web server will be created.
                </p>
                <p>Follow all the steps to create one.</p>
                <p><a href="changeFirst.php">Change username and password</a></p>
                <p>Please Log Out when you are done.
    <?php 
        
    try{   
        //check if documents are present
        $sql = "SELECT count(id) FROM simple_quiz.document;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    }
    catch (PDOexception $e){
         include("../php_lib/myExceptionHandling.inc.php");
         echo myExceptionHandling($e,"../logs/error_log.csv");
    }
             
    //check folders
    //https://w3guy.com/count-number-files-folder/
    function countFolder($dir) {
        return (count(scandir($dir)) - 2);
    }
    $path = getcwd();
    $path = str_replace('manage', 'quiz', $path); 
    $numFolders = countFolder($path)-1;
        
    //check DB and folders
    if ($count ==0){
           
            //$feed = "<br>There is ".$count." DB document $numDir.";
            if ($numFolders == 0){
             
                $feed = ""; 
            }
            if ($numFolders == 1){
             
                $feed = "<br>$numFolders directory present."; 
            }
            if ($numFolders > 1){
    
                $feed = "<br>$numFolders directories present."; 
            }
    }
    
    if ($count ==1){
            if ($numFolders == 0){
                $feed = "<br>There is $count DB document.";
            }
        
            if ($numFolders == 1){
                $numDir ="and $numFolders directory";
                $feed = "<br>There is $count DB document $numDir.";
            }
            if ($numFolders > 1){
                $numDir ="and $numFolders directories";
                $feed = "<br>There is $count DB document $numDir.";
            } 
     }
        
     if ($count> 1){
            
            if ($numFolders == 0){
                $feed = "<br>There are $count DB documents.";
            }
            if ($numFolders == 1){
                $numDir ="and $numFolders directory";
                $feed = "<br>There are $count DB $numDir.";
            }
            if ($numFolders > 1){
                $numDir ="and $numFolders directories";
                $feed = "<br>There are $count DB document $numDir.";
            }
      }

              
    //maximum there are 50 documents inside our db
    //if so erase data
    if (isset($_POST['empty']) && !empty($_POST['empty'])){
        try{
            $sql = "DELETE FROM simple_quiz.percentage;
                DELETE FROM simple_quiz.randomness;
                DELETE FROM simple_quiz.choices;
                DELETE FROM simple_quiz.questions;
                DELETE FROM simple_quiz.group;
                DELETE FROM simple_quiz.document;
                ALTER table simple_quiz.document AUTO_INCREMENT = 1;
                ALTER table simple_quiz.group AUTO_INCREMENT = 1;
                ALTER table simple_quiz.questions AUTO_INCREMENT = 1;
                ALTER table simple_quiz.choices AUTO_INCREMENT = 1;
                ALTER table simple_quiz.randomness AUTO_INCREMENT = 1;
                ALTER table simple_quiz.percentage AUTO_INCREMENT = 1;
            ";
            $pdo->exec($sql);
            $feed = "<br><b><p style=\"color:red\">No DB documents are present</p></b><br>";
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
    }
    if (isset($feed)){
        echo $feed;
    }
    $amount = 50;//used for debug and to empty the DB
    if ($count > $amount){
      //header( 'Location: app.php' ) ;
        echo "<br>
        You can have maximum 50 documents.
        <br>
        <form method=\"post\">
        <input type=\"submit\" value=\"Empty\" class=\"btn btn-info\" name=\"empty\">
        <a href=\"logout.php\" class= \"btn btn-info\">Logout</a>
        </form>";
        
        //stops here
        return;     
    }        
    
    echo "</p>";
    //end check document
    echo "
    <a href=\"../quiz_maker/create_quiz.php\" class= \"btn btn-info\">Begin</a>
    <a href=\"logout.php\" class= \"btn btn-info\">Logout</a>
    ";
    }  
    
    //check if abort input is submitted
            if ($_SESSION['abort'] && $_SESSION['abort']=='yes'){
                $_SESSION["warning"] = '<p style=color:green>Data erased.</p>'."\n";
                //this also prevent infinite loop
                unset($_SESSION['abort']);
                header('Location: app.php');
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

    </html>