<?php
/*
** Copyright (C) 2011 Skynet-Solutions, Inc.
**
********************************************************************************
** Purpose: Firewall Related Functions
********************************************************************************
** Authors:
********************************************************************************
** Cory Claflin <admin@skynet-solutions.net>
**
********************************************************************************
*/

function add_iptables_rule($src_ipaddr,$dst_ipaddr,$host_ipaddr,$src_port,$dst_port)
    {
      exec("sudo -u root /sbin/iptables -t nat -A PREROUTING --src ".$src_ipaddr." --dst ".$host_ipaddr." -p tcp --dport ".$src_port." -j DNAT --to-destination ".$dst_ipaddr.":".$dst_port."");
      exec("sudo -u root /sbin/iptables -t nat -A POSTROUTING -p tcp --dst ".$dst_ipaddr." --dport ".$dst_port." -j SNAT --to-source ".$host_ipaddr."");
      exec("sudo -u root /sbin/iptables -t nat -A OUTPUT --src ".$src_ipaddr." --dst ".$host_ipaddr." -p tcp --dport ".$src_port." -j DNAT --to-destination ".$dst_ipaddr.":".$dst_port."");
    }


function del_iptables_rule($src_ipaddr,$dst_ipaddr,$host_ipaddr,$src_port,$dst_port)
    {
      exec("sudo -u root /sbin/iptables -t nat -D PREROUTING --src ".$src_ipaddr." --dst ".$host_ipaddr." -p tcp --dport ".$src_port." -j DNAT --to-destination ".$dst_ipaddr.":".$dst_port."");
      exec("sudo -u root /sbin/iptables -t nat -D POSTROUTING -p tcp --dst ".$dst_ipaddr." --dport ".$dst_port." -j SNAT --to-source ".$host_ipaddr."");
      exec("sudo -u root /sbin/iptables -t nat -D OUTPUT --src ".$src_ipaddr." --dst ".$host_ipaddr." -p tcp --dport ".$src_port." -j DNAT --to-destination ".$dst_ipaddr.":".$dst_port."");
    }


function check_ip_conntrack($src_ipaddr,$dst_ipaddr,$host_ipaddr,$src_port,$dst_port)
    {
      $ip_conntrack_array = file("/proc/net/ip_conntrack");
      $ip_conntrack = 0;
      foreach($ip_conntrack_array as $key => $value) 
        {
	  if ((preg_match("/ESTABLISHED src=".$src_ipaddr." dst=".$host_ipaddr." sport=/i", $value)) && (preg_match("/dport=".$src_port."/i", $value)) && (preg_match("/src=".$dst_ipaddr." dst=".$host_ipaddr." sport=".$dst_port."/i", $value))){
	  $ip_conntrack = 1;
	  }
        }
      return $ip_conntrack;

    }
?>
