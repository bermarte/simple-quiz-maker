<?php
$end = "";

// fix Headers_already_sent warning

ob_start();
try {
	if (isset($_POST['compile']) && !empty($_POST['compile'])) {

		// latest id is $_SESSION['fk'];
		// select document
		// select a particular user by id
		// there's no form so there should be no problems with SQL injections

		try {
			$stmt = $pdo->prepare("SELECT title, coach, mail, category, numQuestions
                              FROM simple_quiz.document d
                              LEFT JOIN simple_quiz.group g
                              ON d.id = g.id_document
                              WHERE d.id = :id LIMIT 1");
			$stmt->execute([':id' => $_SESSION['fk']]);
			$document = $stmt->fetchAll();
		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		$folder = date("Y-m-d.H:i:s.A");
		mkdir("../quiz/" . $folder, 0755);

		// get link once file is created

		$_SESSION['folder'] = $folder;

		// session booleans and id's

		$Booleans = var_export($_SESSION['Booleans'], true);
		$latestDocumentId = var_export($_SESSION['latestDocumentId'], true);

		// remove garbage

		$Booleans = substr($Booleans, 1, -2);
		$latestDocumentId = substr($latestDocumentId, 1, -2);

		// remove doubles
		// and reorder keys of array

		$arrIds = array_values(array_unique(explode(",", $latestDocumentId)));

		// title is

		$title = $document[0][0];
		$newpage = "../quiz/$folder/$title.php";

		// $contents = file_get_contents($newpage);

		$coach = $document[0][1];
		$mail = $document[0][2];
		$category = $document[0][3];
		$howManyQuestions = $document[0][4];

		// get questions

		try {
			$stmt = $pdo->prepare("SELECT question
                              FROM simple_quiz.questions q
                              LEFT JOIN simple_quiz.group g
                              ON g.id = q.id_group
                              WHERE g.id = :id;");
			$stmt->execute([':id' => $_SESSION['fk']]);
			$questions = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		$contents = "
    <?php
    session_start();
    ?>
    <!DOCTYPE html>
    <html lang= \"en\">
    <head>
    <title>" . htmlentities($title) . "
    </title>
    <meta charset= \"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <!-- stop re-submit -->
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    </script>
     <style>
    body{
        background-color: #c6d7e3;
    }
    #header{
        background-color: #a5c3cb;
        padding: 10px;
        padding-top: 30px;
    }
    h2,h3,h4{
        background-color: #a6d1dd;
        padding: 10px;
    }
    h2{
        font-size: 20px;
    }
    h4{
         font-size: 22px;
    }

    p{
        padding-left:20px;
    }
    #jQuestions{
        background: #a1d1ed;
        padding: 50px;
        margin:30px;
    }
    .JAnswer{
        background: #62baed;
        margin:12px;
        padding: 6px;
    }
    .JAnswer:hover{
        background: #86d07c;
        margin:12px;
        padding: 6px;
    }
    hr {
        background-color: dimgrey !important;
        color: #0c82c6 !important;
        border: solid 2px #0c82c6 !important;
        width: 100% !important;
    }
    input[type=\"radio\"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        border: 2px solid #999;
        transition: 0.2s all linear;
        outline: none;
        margin-right: 5px;
        position: relative;
        top: 4px;
    }
    input:checked {
        border: 6px solid #085480;
    }
    input[type=submit] {
        transition: 0.2s all linear;
        margin-left: 10%;
        margin-right: 10%;
        margin-bottom: 30px;        
        border-radius: 5px;
        width: 80%;
        height:46px;
        font-family: Tahoma;
        font-size: 20px;
        background: #f4f4f4;
        /* https://stackoverflow.com/questions/17236018/how-to-style-submit-button */
        /* Old browsers */
        background: -moz-linear-gradient(top, #f4f4f4 1%, #ededed 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #f4f4f4), color-stop(100%, #ededed));
        /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #f4f4f4 1%, #ededed 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #f4f4f4 1%, #ededed 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #f4f4f4 1%, #ededed 100%);
        /* IE10+ */
        background: linear-gradient(to bottom, #f4f4f4 1%, #ededed 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f4f4f4', endColorstr='#ededed', GradientType=0);
        /* IE6-9 */
    }
    input[type=submit]:hover {
        background: #86d07c;
        cursor: pointer;
        box-shadow: 2px 2px 34px rgba(0, 0, 0, .2);
    }
    .JAnswer:hover,
    label:hover,
    input[type=\"radio\"]:hover{
            cursor: pointer;
        }
    </style>
    </head>
    <body style=\"font-family: sans-serif;\">
    <div id='header'>
    <h2>Coach: " . htmlentities($coach) . "</h2>
    <h3>Email: " . htmlentities($mail) . "</h3>
    <p>" . date("Y/m/d") . "</p>
    <h4>Group: " . htmlentities($category) . "</h4>
    </div>";
		$contents.= '<?php
       
       if ( isset($_SESSION["done"]) ) {
            unset($_SESSION["done"]);
       }
       
        /* TEST:
        echo "<br>' . $Booleans . '<br>";
        echo "<br>' . $latestDocumentId . '<br>";
        */

    ?>';
		$contents.= "
    <!-- questions -->";
		$contents.= "
    <form method='post' action='#results'>
    <div id='jQuestions'>
    ";

		// get all id's from questions

		try {
			$sql = "SELECT id
                FROM simple_quiz.questions
                WHERE id_group = :id_group";
			$result = $pdo->prepare($sql);
			$result->execute([':id_group' => (int)$_SESSION['fk']]);

			// get a simple array

			$questionsIds = $result->fetchAll(PDO::FETCH_COLUMN, 0);
		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		$a = array_reverse($questionsIds);
		for ($i = 0; $i < count($questions); $i++) {
			$q = $i + 1;
			$contents.= "
        <div class ='jQuestionsItems'>
        <hr>
        <p>Question:<br> <b>" . $questions[$i] . "</b></p>
        <div id='JAnswers_$q'>";
			$element = array_pop($a);
			try {
				$stmt = $pdo->prepare("SELECT answer
                           FROM simple_quiz.choices c
                           LEFT JOIN simple_quiz.questions q
                           ON q.id = c.id_questions
                           WHERE q.id = :id");
				$stmt->execute([':id' => (int)$element]);
				$choice = $stmt->fetchAll();
			}

			catch(PDOexception $e) {
				include ("../php_lib/myExceptionHandling.inc.php");

				echo myExceptionHandling($e, "../logs/error_log.csv");
			}

			foreach($choice as $key => $value) {
				$m = $i + 1;
				$contents.= "
            <div class='JAnswer'>
            <label>
            <p>Answer:<br>
            <b>" . htmlentities($value[0]) . "</b>
            <input type= 'radio' name='choice_$m' value='$value[0]'>
            </p>
            </label>
            </div>
            ";
			}

			$contents.= "
        </div>
        </div>
        ";

			// end db

		}

		// get ansers (text)
		// $arrIds:

		$myAnswers = array();
		for ($k = 0; $k < count($arrIds); $k++) {
			try {
				$sql = "SELECT answer
                    FROM simple_quiz.choices
                    WHERE id_questions = :idQuestions
                    AND isRight= '1'";
				$result = $pdo->prepare($sql);
				$result->execute([':idQuestions' => $arrIds[$k]]);

				// get a simple array

				$answers = $result->fetchAll(PDO::FETCH_COLUMN, 0);
			}

			catch(PDOexception $e) {
				include ("../php_lib/myExceptionHandling.inc.php");

				echo myExceptionHandling($e, "../logs/error_log.csv");
			}

			// copy to other array

			array_push($myAnswers, $answers[0]);
		}

		// $myAnswers[n] is the array with the right answers
		// check randomness

		try {
			$sql = "SELECT isRandom
                FROM simple_quiz.randomness r
                LEFT JOIN simple_quiz.document d
                ON d.id = r.id_titleR
                WHERE d.id = :id_document LIMIT 1";
			$result = $pdo->prepare($sql);
			$result->execute([':id_document' => (int)$_SESSION['fk']]);
			$randomness = $result->fetchAll(PDO::FETCH_COLUMN, 0);

			// randomness is in $randomness[0];

		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		// check percentage

		try {
			$sql = "SELECT percent
                FROM simple_quiz.percentage p
                LEFT JOIN simple_quiz.document d
                ON p.id_titleP = d.id
                WHERE d.id = :id_document LIMIT 1";
			$result = $pdo->prepare($sql);
			$result->execute([':id_document' => (int)$_SESSION['fk']]);
			$percent = $result->fetchAll(PDO::FETCH_COLUMN, 0);

			// percent is in $percent[0];

		}

		catch(PDOexception $e) {
			include ("../php_lib/myExceptionHandling.inc.php");

			echo myExceptionHandling($e, "../logs/error_log.csv");
		}

		// calculate percentage in int

		$howmanyQuestions = count($questions);
		function percentage($a, $b)
		{
			$result = ($a / 100) * $b;
			return $result;
		}

		// echo $percent[0]."<br>";
		// echo $howmanyQuestions."<br>";

		$numberOfRightAnswers = round(percentage($percent[0], $howmanyQuestions));

		// create array of numbers counting the answers given

		$countAnswers = array();

		// populate array

		for ($l = 1; $l <= $howmanyQuestions; $l++) {

			// kind of push

			$countAnswers[] = $l;
		}

		$contents.= "
        </div>
        <input type='submit' value='Check results' name='submit'>
        </form>
        <span id='results'>
        </span>";
		$contents.= '<?php 
    
    if(isset($_POST["submit"])) {

      // echo "posted";
      // print_r($_POST);';

		$contents.= '
      echo "<hr>";';
		$contents.= '
    foreach($_POST as $key => $value) {
                 echo "<br><b>You selected:</b><br>".$value;
     
     }
     echo "<hr>";';
		$out.= '
      echo "At least ' . $numberOfRightAnswers . ' answer(s) to the questions should be right<br>";';

		// $contents .="<hr>";

		for ($i = 0; $i < count($myAnswers); $i++) {
			$out.= '
      $values = array_values($_POST);
      
      foreach($values as $key => $value) {
            
            if ($value == "' . $myAnswers[$i] . '"){
                
                echo "<br><b>Ok, right answer:</b><br>".$value;  
                $_SESSION["got"]+=1;
           }

         // to show the feedback later

         $_SESSION["done"] ="on";
         
      }';
		}

		$contents.= $out;
		$contents.= '
      /*
      NOTE:
      echo "<hr>";

      // $myAnswers[n] does not exists here

       for ($x = 0; $x <= 10; $x++) {
            echo "The number is: $x <br>";
       } 
       */
    
    } 
    ?>';
		if ($randomness[0] == "1") {
			$contents.= '
    
      <script>

      // js script 

      function randomizeDivs(j) {
                var list = document.querySelector(j), i;
                for (i = list.children.length; i >= 0; i--) {
                    list.appendChild(list.children[Math.random() * i | 0]);
                }
      }
      randomizeDivs("#jQuestions");
      var myArray = Array.from(document.querySelectorAll(".jQuestionsItems")); 
      function shuffleIds(){
            for (k=0;k<myArray.length;k++){
                n=k+1;
                randomizeDivs("#JAnswers_"+n);
            }
        }
          
          
      randomizeDivs("#jQuestions");
      shuffleIds();
      
      </script>';
		}

		// check results

		$contents.= '
    <?php
    if (isset($_SESSION["done"])){
        echo "<hr>";

        // $newNum = $num-1;
        // echo $_SESSION["got"];

        if (!isset($_SESSION["got"])){
            echo "<br>There are no answer(s) right.";
        }
        else{
           $res = $_SESSION["got"];
           echo "<br>There are $res answer(s) right.";
        }
        if ($_SESSION["got"] >= ' . $numberOfRightAnswers . '){
            echo "<br><span style=\"color:green;font-weight:bold\">You did it.</span>";
        }
        else{
            echo "<br><span style=\"color:red;font-weight:bold\">Not enough.</span>";
        }
        header("Location: ".$_SERVER["PHP_SELF"]);
        unset($_SESSION["done"]);
        unset($_SESSION["got"]);
    }
    ?>
    ';
		$contents.= '
    </body>     
    </html>';
		file_put_contents($newpage, $contents);
		$path = getcwd();
		$path = str_replace('quiz_maker', 'quiz' . "/$folder/", $path);

		// complete file is

		$file = $path . $title . ".php";

		// check if file is present

		$dir = $path;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file) {

						// $end = 'File created in '. "<b>$path</b>";

						$_SESSION['success'] = 'File created in ' . "<b>$path</b>";

						// echo 'File created in '. "<b>$path</b>";

						$_SESSION['stoprestartSwitch'] = 'ok';
						$_SESSION['path'] = $path;
						header('Location: create_quiz_E.php');
						return;
					}
				}

				closedir($dh);
			}
		}

		return;
	}
} //end try
catch(Exception $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}

catch(Error $e) {
	include ("../php_lib/myExceptionHandling.inc.php");

	echo myExceptionHandling($e, "../logs/error_log.csv");
}