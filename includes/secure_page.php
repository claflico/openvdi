<?php
  // log out if no session or user deleted
  $auth = false;
  if (isset($_SESSION['user_id'])) {
	$result = mysql_query("SELECT COUNT(*) FROM users WHERE user_id = '" . addslashes($_SESSION['user_id']) . "' AND user_enabled='1'");
	if ($result) {
	  if ($frow = mysql_fetch_row($result)) {
		if ($frow[0] > 0) {
		  $auth = true;
		}
	  }
	}
  }

  if (isset($_SESSION['user_groups'])) {
  $new_user_groups_array=array();
  $user_groups_array=$_SESSION['user_groups'];
    foreach ($user_groups_array as $key => $value)
     {
	$result = mysql_query("SELECT COUNT(*) FROM user_groups WHERE group_id = '" . $value . "' AND group_enabled='1'");
	if ($result) {
	  if ($frow = mysql_fetch_row($result)) {
		if ($frow[0] > 0) {
		  array_push($new_user_groups_array,$value);
		  $auth = true;
		}
	  }
	}
     }
    if (count($new_user_groups_array) >= 1) {
      $_SESSION['user_groups'] = $new_user_groups_array;
    } else {
      unset($_SESSION['user_groups']);
    }
  }

  if (!$auth) {
	if (isset($_SESSION)) {
	  while (list ($key, $val) = each ($_SESSION)) {
		session_unregister($key);
	  }
	  session_destroy();
	}
	header("Location: login.php");
	exit();
		
  }
?>
