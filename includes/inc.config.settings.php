<?php
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: Get global settings
********************************************************************************
** Authors:
********************************************************************************
** Cory Claflin <admin@skynet-solutions.net>
**
********************************************************************************
*/

$dbConn = connectDB($dbHost, $dbUser, $dbPass, $dbDB);
if (!$dbConn) {
	die("Database is currently down...please try again later");
}

$result = mysql_query("SELECT * FROM global_settings");

if ($result) {
	#if ($row = mysql_fetch_row($result)) {
	while($row = mysql_fetch_array($result)) {
	$setting_auth_type = $row['setting_auth_type'];
	$setting_desktop_type_default = $row['setting_desktop_type_default'];
	$setting_audit_log_enabled = $row['setting_audit_log_enabled'];
	}
}

if ($setting_audit_log_enabled=="1") {
     $audit_log_event_types = array();
     //ugly fix to make array key equal type id
     array_push($audit_log_event_types,"0");
     $result = mysql_query("SELECT * FROM audit_log_event_types");
     if ($result) {
	while($row = mysql_fetch_array($result)) {
	    $audit_log_event_type = array('id' => $row['log_event_type_id'],'name' => $row['log_event_type_name'],'description' => $row['log_event_type_desc'],'enabled' => $row['log_event_type_enabled']);
	array_push($audit_log_event_types,$audit_log_event_type);
	}
     }
}

//get the user's ip address
$user_ipaddr =$_SERVER['REMOTE_ADDR'];

//get the host ip address
if ($iptables_ipaddr == "") 
$iptables_ipaddr = $_SERVER["SERVER_ADDR"];

?>
