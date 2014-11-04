<?php 
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: List Desktops
********************************************************************************
*/

  require 'inc.config.php';
  require 'lib/db.php';
  require 'includes/inc.functions.common.php';
  require 'includes/inc.config.settings.php';
  require 'lib/form_functions.php';

  session_start('USERS');

  require 'includes/secure_page.php';

  $user_id='';
  if (isset($_SESSION['user_id'])) {
    $user_id=addslashes($_SESSION['user_id']);
  }

  $user_name='';
  if (isset($_SESSION['user_name'])) {
    $user_name=addslashes($_SESSION['user_name']);
  }

  $user_groups_array=array();
  if (isset($_SESSION['user_groups'])) {
    $user_groups_array=$_SESSION['user_groups'];
  }

?>
<html><head>
<title>Desktops</title>
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
		<table width="100%">
		  <tr>
			<td><?php echo "Desktops"; ?></td>
			<td align="right"><a href="logout.php">Logout</a></td>
		  </tr>
		</table>
	  </td>
	</tr>
	<tr>
	  <td>
		<table border="1">
		  <tr>
			<td><?php echo "Name"; ?></td>
			<td><?php echo "Operating System"; ?></td>
			<td colspan="2" align="center"><?php echo "Action"; ?></td>
		  </tr>
		<?php
		$sql_query = " desktops.desktop_id IS NULL";
	        if ($user_id != "") {
		  $sql_query = $sql_query." OR desktop_entitlements.user_id='".$user_id."'";
	        }
	        if (count($user_groups_array) >= 1) {
		 foreach ($user_groups_array as $key => $value)
		   {
		     $sql_query = $sql_query." OR desktop_entitlements.group_id='".$value."'";	
		   }
	        }
		$result2 = mysql_query("SELECT desktops.desktop_id,desktop_display_name,desktop_user_editable,operating_system_name FROM desktops,desktop_entitlements,desktop_operating_systems,desktop_remote_protocols WHERE desktops.desktop_id=desktop_entitlements.desktop_id AND desktops.operating_system_id=desktop_operating_systems.operating_system_id AND desktops.remote_protocol_id=desktop_remote_protocols.remote_protocol_id AND desktop_enabled='1' AND remote_protocol_enabled='1' AND (".$sql_query.") ORDER BY desktop_display_name ASC");

		while($row = mysql_fetch_array($result2))
		  {
		?>
		  <tr>
			<td><?php echo $row['desktop_display_name']; ?></td>
			<td><?php echo $row['operating_system_name']; ?></td>

			<?php if ($row['desktop_user_editable']=='1') { ?>
			<td><a href="edit.php?desktop_id=<?php echo $row['desktop_id']; ?>">EDIT</a></td>
			<?php } ?>

			<td><a href="connect.php?desktop_id=<?php echo $row['desktop_id']; ?>">CONNECT</a></td>
		  </tr>
		<?php
		  }
		?>
	</td>
	</tr>
  </table>
</body>
</html>
