<?php 
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: User Login
********************************************************************************
*/

  require 'inc.config.php';
  require 'lib/db.php';
  require 'includes/inc.functions.common.php';
  require 'includes/inc.config.settings.php';
  require 'lib/form_functions.php';

  unset($error);
 
  if (isset($_POST['login'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
	  $error = "Please enter your username and password.";
	}
	
	elseif (isUsername($_POST['username'])!= TRUE)  {
	  $error = "Username must contain only letters and numbers.";
	}
	
	else {
	  if ($setting_auth_type=="local") {
	    $result = mysql_query("SELECT user_id,user_name FROM users WHERE user_name = '" . prepareData($_POST['username']) . "' AND user_password = MD5('" . prepareData($_POST['password']) . "') AND user_enabled = '1'");
	    if ($result) {
		  if ($frow = mysql_fetch_row($result)) {
		    $user_id = $frow[0];
		    $user_name = $frow[1];

		      //get the user's groups
		      $user_groups_array = get_user_groups($user_id);

		      session_start('USERS');
		      $_SESSION['user_id'] = $user_id;
		      $_SESSION['user_name'] = $user_name;
		      if (count($user_groups_array) >= 1) {
		      $_SESSION['user_groups'] = $user_groups_array;
		      }
		      session_write_close();
		  
		      //if audit log is enabled and logins are logged
		      if (($setting_audit_log_enabled=="1") && ($audit_log_event_types[1]['enabled']=="1")) {
			log_user_event($user_name,$user_ipaddr,$audit_log_event_types[1]['description']);
		      }
		      // landing page
		      header("Location: desktops.php");
		      exit(); 
		  }
		  else {
		    $error = "Invalid username or password.";
		  }
	    }
	  } //$setting_auth_type=="local"
	  //elseif ($setting_auth_type=="ldap") {
	  //} //$setting_auth_type=="ldap"
	  else {
		$error = mysql_error();
	  }
	}
  }

?>
<html><head>
<title>Login</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NOCACHE">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</HEAD>
<BODY>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

  <table border="1" align="center" >
	<tr>
	  <td>
		<table>
		  <tr>
			<td><?php echo "Login"; ?></td>
		  </tr>
		</table>
	  </td>
	</tr>
	<?php if (isset($error)) { echo "<tr><td><table><tr><td class=\"error\">$error</td></tr></table></td></tr>"; } ?>
	<tr>
	  <td>
		<table>
		  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<tr>
			  <td><?php echo "Username"; ?>:</td>
			  <td>
				<input type="text" name="username" size="15" maxlength="15" value="<?php if (isset($_POST['username'])) { echo htmlChars($_POST['username']); } ?>">
			  </td>
			</tr>
			<tr>
			  <td><?php echo "Password"; ?>:</td>
			  <td>
				<input type="password" name="password" size="15" maxlength="14" value="<?php if (isset($_POST['password'])) { echo htmlChars($_POST['password']); } ?>">  
			  </td>
			</tr>
			<tr><td>&nbsp;</td><td><input type="Submit" name="login" value="<?php echo "Login"; ?>"></td></tr>
		  </form>
		</table><br>
	</td>
	</tr>
  </table>
</body>
</html>
