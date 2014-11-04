<?php 
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: Edit desktop
********************************************************************************
*/

  require 'inc.config.php';
  require 'lib/db.php';
  require 'includes/inc.functions.common.php';
  require 'includes/inc.config.settings.php';
  require 'lib/form_functions.php';

  session_start('USERS');

  require 'includes/secure_page.php';

  $user_id=addslashes($_SESSION['user_id']);
  $user_name=addslashes($_SESSION['user_name']);

  $desktop_id=$_GET["desktop_id"];

  $sql_query = "  desktop_entitlements.user_id=".addslashes($_SESSION['user_id'])."";
  $result = mysql_query("SELECT group_id from users_groups_members WHERE user_id='".$user_id."'");
  while($row = mysql_fetch_array($result))
    {
     $sql_query = $sql_query." OR desktop_entitlements.group_id='".$row['group_id']."'";	
    }
  //confirm user entitled either via user or group id
  $result = mysql_query("SELECT desktop_name FROM desktops,desktop_entitlements WHERE desktops.desktop_id=desktop_entitlements.desktop_id AND desktops.desktop_id=".$desktop_id." AND desktops.desktop_enabled='1' AND (".$sql_query.")");

  //if no entitled desktop
  if (mysql_num_rows($result)=="0") {
   exit();
  }
  //if entitled desktop
  while($row = mysql_fetch_array($result))
     {
        $desktop_name=trim($row['desktop_name']);
     }


  $rdp_file_array = array();
  $result2 = mysql_query("SELECT rdp_parameter_name,rdp_parameter_type,rdp_parameter_default FROM rdpfile_parameters WHERE rdp_parameter_enabled='1' ORDER BY rdp_parameter_name ASC");
  while($row = mysql_fetch_array($result2))
     {
	$line = trim($row['rdp_parameter_name']).":".$row['rdp_parameter_type'].":".trim($row['rdp_parameter_default'])."\r\n";
	if (preg_match("/<-DESKTOP_IPADDRESS->/i", $line)) {
	   $line = $row['rdp_parameter_name'].":".$row['rdp_parameter_type'].":".$desktop_ip_address."\r\n";
	   }
	if (preg_match("/<-USER_NAME->/i", $line)) {
	   $line = $row['rdp_parameter_name'].":".$row['rdp_parameter_type'].":".$user_name."\r\n";
	   }
	array_push($rdp_file_array,$line);	
     }



?>
