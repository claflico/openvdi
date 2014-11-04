<?php
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
**
********************************************************************************
** Purpose: Frequently Used Functions
********************************************************************************
** Authors:
********************************************************************************
** Cory Claflin <admin@skynet-solutions.net>
**
********************************************************************************
*/

function array_to_file($array,$file)
    {
	exec("sudo -u root chmod 666 ".$file);
	$fw = fopen($file,"w");
	fwrite($fw,implode($array));
	fclose($fw);
	exec("sudo -u root chmod 644 ".$file);
    }

function string_to_file($string,$file)
    {
	exec("sudo -u root chmod 666 ".$file);
	$fw = fopen($file,"w");
	fwrite($fw,$string);
	fclose($fw);
	exec("sudo -u root chmod 644 ".$file);
    }

function delete_file($file)
    {
	exec("sudo -u root chmod 777 ".$file);
	unlink($file);
    }

function get_user_groups($user_id)
    {
	$user_groups_array = array();
	$result = mysql_query("SELECT users_groups_members.group_id from users_groups_members,user_groups WHERE users_groups_members.group_id=user_groups.group_id AND user_id='".$user_id."' AND group_enabled='1'");
	while($row = mysql_fetch_array($result))
	{
	  array_push($user_groups_array,$row['group_id']);
	}
	return $user_groups_array;
    }

function log_user_event($user_name,$user_ipaddr,$event_desc)
    {
	mysql_query("INSERT INTO audit_log_user (log_entry_user_name,log_entry_user_ipaddr,log_entry_info) VALUES ('".$user_name."','".$user_ipaddr."','".$event_desc."')");
    }

?>
