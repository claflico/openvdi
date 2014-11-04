<?php
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
**
********************************************************************************
** Purpose: Connection File Related Functions
********************************************************************************
** Authors:
********************************************************************************
** Cory Claflin <admin@skynet-solutions.net>
**
********************************************************************************
*/

function make_rdp_file ($connect_file_dir,$desktop_id,$desktop_name,$desktop_ip_address,$desktop_connect_port,$user_name,$user_id,$install_mode,$iptables_ipaddr)
  {

  // First create the launch directory if not exist
  if (!file_exists($connect_file_dir)){mkdir($connect_file_dir,0777);}

  // Now set the pathname+filename
  $full_file_name = $connect_file_dir.$desktop_name.".rdp";

  // Delete the RDP file if already exists
  if (file_exists($full_file_name)) {$tmp=(unlink($full_file_name));}

  //get the enabled RDP values
  $rdp_parameter_array = array();
  $result2 = mysql_query("SELECT rdp_parameter_id,rdp_parameter_name,rdp_parameter_type,rdp_parameter_default FROM rdpfile_parameters WHERE rdp_parameter_enabled='1' ORDER BY rdp_parameter_name ASC");
  while($row = mysql_fetch_array($result2))
     {
	$rdp_parameter_id = $row['rdp_parameter_id'];
	$rdp_parameter_name = $row['rdp_parameter_name'];
	$rdp_parameter_type = $row['rdp_parameter_type'];
	if (preg_match("/<-DESKTOP_IPADDRESS->/i", $row['rdp_parameter_default'])) {
	  if ($install_mode == "security") {
	     $rdp_parameter_value = $iptables_ipaddr;
	  } else {
	     $rdp_parameter_value = $desktop_ip_address;
	  }
	} elseif (preg_match("/<-USER_NAME->/i", $row['rdp_parameter_default'])) {
	   $rdp_parameter_value = $user_name;
	} elseif (preg_match("/<-CONNECT_PORT->/i", $row['rdp_parameter_default'])) {
	   $rdp_parameter_value = $desktop_connect_port;
	} else {
	   $rdp_parameter_value = $row['rdp_parameter_default'];
	}
	$parameter_array = array('id' => $rdp_parameter_id,'name' => $rdp_parameter_name,'type' => $rdp_parameter_type,'value' => $rdp_parameter_value);
	array_push($rdp_parameter_array,$parameter_array);
     }

  //get the user saved settings if any for each parameter
  $rdp_file_array = array();
  foreach($rdp_parameter_array as $key => $value) 
     {
	if ($user_id != "") {
	  $result3 = mysql_query("SELECT rdp_parameter_value FROM user_rdp_settings WHERE user_id='".$user_id."' AND desktop_id='".$desktop_id."' AND rdp_parameter_id='".$rdp_parameter_array[$key]['id']."'");
	  //replace any default values with 
	  //user saved values
	  if (mysql_num_rows($result3)>0) {
	     $rdp_parameter_array[$key]['value'] = mysql_result($result3,0);
	  }
	}
	array_push($rdp_file_array,$rdp_parameter_array[$key]['name'].":".$rdp_parameter_array[$key]['type'].":".$rdp_parameter_array[$key]['value']."\r\n");	
     }

  if (!$file_handle = fopen($full_file_name,"a")) { echo "Cannot open file"; } 
  else
  // Create an RDP file 
  array_to_file($rdp_file_array,$full_file_name);

  } //end function make_rdp
?>
