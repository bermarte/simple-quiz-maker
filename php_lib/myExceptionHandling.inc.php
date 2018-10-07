<?php

/*
The MIT License (MIT)

Copyright (c) Wed Jan 03 2018 Micky De Pauw

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORTOR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * Deze functie verwerkt "exceptions" op een
 * gestandardiseerde manier
 * indien het script op een test/design omgeving
 * uitgevoerd wordt (localhost) zal de gebruiker
 * een complete boodschap krijgen 
 * (fout, script, lijnnummer)
 * 
 * Indien het script in een productie-omgeving
 * uitgevoerd wordt krijgt de gebruiker slechts 
 * een sumiere boodschap
 * 
 * in beide gevallen wordt de exception "gelogged"
 * (datum-tijd, fout, script, lijnnummer) in de
 * opgegeven logfile (csv)
 * 
 * @author Micky De Pauw
 * @param  [object] $_exception [exception]
 * @param  [string] $_logfile   [log-file + folder]
 * @return [string] [user-message]
 */
function myExceptionHandling($_exception, $_logfile)
{
  if ($_SERVER['SERVER_NAME'] != "localhost")
  {
    header('Location: unavailable.php');
  }
  else
  {

    $_msg = "
    <div id=container>
    <div id=exception-container class=class-container>
    <hr>
    <strong>EXCEPTION</strong><br><br>
    <b>Error: </b>".$_exception->getMessage()."<br><br>
    <b>File: </b>".$_exception->getFile()."<br>
    <b>Line: </b>".$_exception->getLine()."<br>
    <hr>
    </div>
    </div>
    ";

  }
  
// exception log
  $_error_log[1] = strftime("%d-%m-%Y %H:%M:%S");
  $_error_log[2] = $_exception->getMessage();
  $_error_log[3] = $_exception->getFile();
  $_error_log[4] = $_exception->getLine();
  
  $_pointer = fopen("$_logfile","ab");
  fputcsv($_pointer, $_error_log);
  fclose($_pointer);
// user-message  
  return $_msg;

}
 

?>
