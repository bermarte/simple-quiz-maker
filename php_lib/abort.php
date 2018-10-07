<?php
session_start();
include_once "../php_lib/pdo.inc.php";
//include_once "../php_lib/erase.inc.php";
ob_start();
try{
    //erase db from create_quiz_B.php
    if (isset($_POST['abort']) && !empty($_POST['abort'])){
        
        //erase SESSION if starting again from create_quiz.php
        $_SESSION['debug'] = TRUE;
        try{
            $sql = 'DELETE FROM document
            WHERE document.id = :document_id;
            ALTER table simple_quiz.document AUTO_INCREMENT = 1;';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':document_id'=>$_SESSION['document.id']
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        $_SESSION['abort']="yes";
        header( "Location: ../manage/app.php" );
    }
    //erase db from create_quiz_C.php
    if (isset($_POST['abortb']) && !empty($_POST['abortb'])){
        
        //erase SESSION if starting again from create_quiz.php
        $_SESSION['debug'] = TRUE;
        try{
            $sql = 'DELETE simple_quiz.group
            FROM simple_quiz.group
            INNER JOIN document
            ON group.id_document = document.id
            WHERE document.id = :document_id;
            ALTER table simple_quiz.group AUTO_INCREMENT = 1;
            DELETE FROM document
            WHERE document.id = :document_id;
            ALTER table simple_quiz.document AUTO_INCREMENT = 1;
            ';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':document_id'=>$_SESSION['document.id']
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        $_SESSION['abort']="yes";
        header( "Location: ../manage/app.php" );
    }
    //erase db from create_quiz_D.php
    if (isset($_POST['abortc']) && !empty($_POST['abortc'])){
        //use $_SESSION["group.id"] to obtain the id of the group
        //erase SESSION if starting again from create_quiz.php
        $_SESSION['debug'] = TRUE;
        //bind $_SESSION["group.id"]
        try{
            $sql = '
            DELETE simple_quiz.questions
            FROM simple_quiz.questions
            INNER JOIN simple_quiz.group
            ON questions.id_group = group.id
            WHERE group.id = :group_id;
            ALTER table simple_quiz.questions AUTO_INCREMENT = 1;
            DELETE simple_quiz.group
            FROM simple_quiz.group
            INNER JOIN document
            ON group.id_document = document.id
            WHERE document.id = :document_id;
            ALTER table simple_quiz.group AUTO_INCREMENT = 1;
            DELETE FROM document
            WHERE document.id = :document_id;
            ALTER table simple_quiz.document AUTO_INCREMENT = 1;
            ';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':group_id'=>$_SESSION["group.id"],
                ':document_id'=>$_SESSION['document.id']
            ));
        }
        catch (PDOexception $e){
            include("../php_lib/myExceptionHandling.inc.php");
            echo myExceptionHandling($e,"../logs/error_log.csv");
        }
        $_SESSION['abort']="yes";
        header( "Location: ../manage/app.php" );
    }
    //erase db from create_quiz_E.php
    if (isset($_POST['abortd']) && !empty($_POST['abortd'])){
        
        /*
        check first if form is being submitted or not
        using $_SESSION['form_submitted'] from create_quiz_E.php 
        */
        if (!$_SESSION['form_submitted']){
            
            //erase SESSION if starting again from create_quiz.php
            $_SESSION['debug'] = TRUE;
            //first part is a double Join based on $_SESSION["group.id"];
            try{
                $sql = '
                DELETE simple_quiz.choices
                FROM simple_quiz.choices
                INNER JOIN simple_quiz.questions
                ON choices.id_questions = questions.id
                INNER JOIN simple_quiz.group
                ON questions.id_group = group.id
                WHERE group.id = :group_id;
                ALTER table simple_quiz.choices AUTO_INCREMENT = 1;
                
                DELETE simple_quiz.questions
                FROM simple_quiz.questions
                INNER JOIN simple_quiz.group
                ON questions.id_group = group.id
                WHERE group.id = :group_id;
                ALTER table simple_quiz.questions AUTO_INCREMENT = 1;
                DELETE simple_quiz.group
                FROM simple_quiz.group
                INNER JOIN document
                ON group.id_document = document.id
                WHERE document.id = :document_id;
                ALTER table simple_quiz.group AUTO_INCREMENT = 1;
                DELETE FROM document
                WHERE document.id = :document_id;
                ALTER table simple_quiz.document AUTO_INCREMENT = 1;
                
                ';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':group_id'=>$_SESSION["group.id"],
                    ':document_id'=>$_SESSION['document.id'] 
                ));
            }
            catch (PDOexception $e){
                include("../php_lib/myExceptionHandling.inc.php");
                echo myExceptionHandling($e,"../logs/error_log.csv");
            }
            
        }
        else{
            try{
                $sql = '
                DELETE simple_quiz.randomness
                FROM simple_quiz.randomness
                INNER JOIN simple_quiz.document
                ON randomness.id_titleR = document.id
                WHERE document.id = :document_id;
                ALTER table simple_quiz.randomness AUTO_INCREMENT = 1;
                
                DELETE simple_quiz.percentage
                FROM simple_quiz.percentage
                INNER JOIN simple_quiz.document
                ON percentage.id_titleP = document.id
                WHERE document.id = :document_id;
                ALTER table simple_quiz.percentage AUTO_INCREMENT = 1;
                
                DELETE simple_quiz.choices
                FROM simple_quiz.choices
                INNER JOIN simple_quiz.questions
                ON choices.id_questions = questions.id
                INNER JOIN simple_quiz.group
                ON questions.id_group = group.id
                WHERE group.id = :group_id;
                ALTER table simple_quiz.choices AUTO_INCREMENT = 1;
                
                DELETE simple_quiz.questions
                FROM simple_quiz.questions
                INNER JOIN simple_quiz.group
                ON questions.id_group = group.id
                WHERE group.id = :group_id;
                ALTER table simple_quiz.questions AUTO_INCREMENT = 1;
                DELETE simple_quiz.group
                FROM simple_quiz.group
                INNER JOIN document
                ON group.id_document = document.id
                WHERE document.id = :document_id;
                ALTER table simple_quiz.group AUTO_INCREMENT = 1;
                DELETE FROM document
                WHERE document.id = :document_id;
                ALTER table simple_quiz.document AUTO_INCREMENT = 1;
                ';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':document_id'=>$_SESSION['document.id'],
                    ':group_id'=>$_SESSION["group.id"]
                ));
            }
            catch (PDOexception $e){
                include("../php_lib/myExceptionHandling.inc.php");
                echo myExceptionHandling($e,"../logs/error_log.csv");
            }
        }
        $_SESSION['abort']="yes";
        header( "Location: ../manage/app.php" );
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

