<?php 
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: User Logout
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

  //if audit log is enabled and logouts are logged
  if (($setting_audit_log_enabled=="1") && ($audit_log_event_types[2]['enabled']=="1")) {
	log_user_event($user_name,$user_ipaddr,$audit_log_event_types[2]['description']);
  }

  while (list ($key, $val) = each ($_SESSION)) {
	session_unregister($key);
  }
  session_destroy();

  header("Location: ./login.php");
 
  
?>
