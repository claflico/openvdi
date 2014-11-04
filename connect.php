<?php 
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
********************************************************************************
** Purpose: Connect to desktop
********************************************************************************
*/

  require 'inc.config.php';
  require 'lib/db.php';
  require 'includes/inc.functions.common.php';
  require 'includes/inc.functions.connect.php';
  require 'includes/inc.functions.iptables.php';
  require 'includes/inc.config.settings.php';
  require 'lib/form_functions.php';

  session_start('USERS');

  require 'includes/secure_page.php';

  //get the user_id
  $user_id='';
  if (isset($_SESSION['user_id'])) {
    $user_id=addslashes($_SESSION['user_id']);
  }

  //get the user_name
  $user_name='';
  if (isset($_SESSION['user_name'])) {
    $user_name=addslashes($_SESSION['user_name']);
  }

  //get the user_groups
  $user_groups_array=array();
  if (isset($_SESSION['user_groups'])) {
    $user_groups_array=$_SESSION['user_groups'];
  }

  //get the desktop_id
  $desktop_id='';
  if (isset($_GET["desktop_id"])) {
   $desktop_id=addslashes($_GET["desktop_id"]);
  } else {
   header("Location: error.php");
  }

  //build the query
  $sql_query = "desktops.desktop_id IS NULL";
  if ($user_id != "") {
    $sql_query = $sql_query." OR desktop_entitlements.user_id='".$user_id."'";
  }
  if (count($user_groups_array) >= 1) {
   foreach ($user_groups_array as $key => $value)
    {
     $sql_query = $sql_query." OR desktop_entitlements.group_id='".$value."'";	
    }
  }

  //confirm user entitled either via user or group id
  $result = mysql_query("SELECT desktop_name,desktop_ip_address,desktops.remote_protocol_id FROM desktops,desktop_entitlements,desktop_remote_protocols WHERE desktops.desktop_id=desktop_entitlements.desktop_id AND desktops.remote_protocol_id=desktop_remote_protocols.remote_protocol_id AND remote_protocol_enabled='1' AND desktops.desktop_id='".$desktop_id."' AND desktops.desktop_enabled='1' AND (".$sql_query.")");

  //if no entitled desktop
  if (mysql_num_rows($result)=="0") {
   header("Location: logout.php");
   exit();
  }
  //if entitled desktop
  while($row = mysql_fetch_array($result))
     {
        $desktop_name=trim($row['desktop_name']);
        $desktop_ip_address=trim($row['desktop_ip_address']);
        $remote_protocol_id=trim($row['remote_protocol_id']);
     }

  //get the protocol port info
  $result2 = mysql_query("SELECT * FROM desktop_remote_protocols WHERE remote_protocol_id = '".$remote_protocol_id."'");
  while($row2 = mysql_fetch_array($result2))
     {
        $remote_protocol_defport=trim($row2['remote_protocol_defport']);
        $remote_protocol_minport=trim($row2['remote_protocol_minport']);
        $remote_protocol_maxport=trim($row2['remote_protocol_maxport']);
     }

  //specify port to connect to
  $desktop_connect_port = $remote_protocol_defport;

  if ($install_mode == "security") {
    //check existing sessions
    //look for sessions from user's ip
    $result3 = mysql_query("SELECT * FROM desktop_remote_sessions WHERE session_src_ipaddr = '".$user_ipaddr."' AND session_is_active = '1'");
    //no active sessions
    if (mysql_num_rows($result3) == 0) {
     //add the firewall rule
     add_iptables_rule($user_ipaddr,$desktop_ip_address,$iptables_ipaddr,$desktop_connect_port,$desktop_connect_port);
     //insert the session info
     mysql_query("INSERT INTO desktop_remote_sessions (session_user_name,session_src_ipaddr,session_dst_ipaddr,session_src_port,session_dst_port,remote_protocol_id) VALUES ('".$user_name."','".$user_ipaddr."','".$desktop_ip_address."','".$desktop_connect_port."','".$desktop_connect_port."','".$remote_protocol_id."')");
    //active session from user's ip
    } else {
     while($row3 = mysql_fetch_array($result3))
        {
        $session_id=trim($row3['session_id']);
        $session_user_name=trim($row3['session_user_name']);
        $session_src_ipaddr=trim($row3['session_src_ipaddr']);
        $session_src_port=trim($row3['session_src_port']);
        $session_dst_ipaddr=trim($row3['session_dst_ipaddr']);
        $session_dst_port=trim($row3['session_dst_port']);

        //check to see if session is connected
        $session_is_connected = check_ip_conntrack($session_src_ipaddr,$session_dst_ipaddr,$iptables_ipaddr,$session_src_port,$session_dst_port);
        echo $session_is_connected;

        //check if session_user_name is current user
        if (($user_name == $session_user_name) && ($session_is_connected == '0')) {
         //delete the firewall rule
         //del_iptables_rule($session_src_ipaddr,$session_dst_ipaddr,$iptables_ipaddr,$session_src_port,$session_dst_port);
         //close the active session
         mysql_query("UPDATE desktop_remote_sessions SET session_end_timestamp = '".date("Y-m-d H:i:s")."',session_is_active='0' WHERE session_id='".$session_id."'");
        }
       }
    }
  } //end install_mode == "security" 

  make_rdp_file($connect_file_dir,$desktop_id,$desktop_name,$desktop_ip_address,$desktop_connect_port,$user_name,$user_id,$install_mode,$iptables_ipaddr);
  $full_file_name = $connect_file_dir.$desktop_name.".rdp";

  //log the event
  if (($setting_audit_log_enabled=="1") && ($audit_log_event_types[3]['enabled']=="1")) {
     $audit_log_event_types[3]['description'] = str_replace("<-DESKTOP_NAME->",$desktop_name,$audit_log_event_types[3]['description']);
     $audit_log_event_types[3]['description'] = str_replace("<-DESKTOP_IPADDRESS->",$desktop_ip_address,$audit_log_event_types[3]['description']);
     log_user_event($user_name,$user_ipaddr,$audit_log_event_types[3]['description']);
  }




  #header("Content-type: application/force-download");
  //header("Content-type: application/rdp");
  #header("Content-Transfer-Encoding: Binary");
  #header("Content-length: ".filesize($full_file_name));
  //header("Content-disposition: attachment; filename=\"".$full_file_name."\"");



  //readfile("$full_file_name");

?>
