<?php
//if(isset($_POST['submit']) && !empty($_POST['submit'])) {
if (isset($_POST['empty']) && !empty($_POST['empty'])){
    try{
        $sql = "DELETE FROM simple_quiz.percentage;";
        // use exec() because no results are returned
        $conn->exec($sql);
    }
    catch (PDOexception $e){
         include("../php_lib/myExceptionHandling.inc.php");
         echo myExceptionHandling($e,"../logs/error_log.csv");
    }
}

/*
DROP TABLE IF EXISTS simple_quiz.percentage;
DROP TABLE IF EXISTS simple_quiz.randomness;
DROP TABLE IF EXISTS simple_quiz.choices;
DROP TABLE IF EXISTS simple_quiz.questions;
DROP TABLE IF EXISTS simple_quiz.group;
DROP TABLE IF EXISTS simple_quiz.document;
DROP TABLE IF EXISTS simple_quiz.users;
*/