<?php
$host = 'localhost';
$port = 8889;
$db = 'simple_quiz';
$location = 'mysql:host='.$host.';port='.$port.';dbname='.$db.';';
$user = 'user';
$password = 'password';
$pdo = new PDO($location,$user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
$pdo = new PDO('mysql:host=localhost;port=8889;dbname=simple_quiz', 
  'root', 'root');
*/
