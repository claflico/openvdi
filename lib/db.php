<?php


  function connectDB($host, $user, $pass, $db) {
  
	$dbconn = @mysql_connect($host, $user, $pass);
	if ($dbconn) {
	  if (@mysql_select_db($db,$dbconn)) {
		return $dbconn;
	  }
	}
  }
  
  
?>
