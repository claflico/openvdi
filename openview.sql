-- phpMyAdmin SQL Dump
-- version 2.11.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 05, 2011 at 05:19 PM
-- Server version: 5.0.77
-- PHP Version: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `openview`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int(5) NOT NULL auto_increment,
  `admin_name` varchar(50) NOT NULL,
  `admin_password` varchar(255) default NULL,
  `admin_uid` varchar(255) default NULL,
  `admin_enabled` int(1) NOT NULL default '1',
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_password`, `admin_uid`, `admin_enabled`) VALUES
(1, 'admin', 'admin', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_groups`
--

CREATE TABLE IF NOT EXISTS `admin_groups` (
  `group_id` int(5) NOT NULL auto_increment,
  `group_name` varchar(50) NOT NULL,
  `group_gid` varchar(255) default NULL,
  `group_enabled` int(1) NOT NULL default '1',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admin_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `audit_log_event_types`
--

CREATE TABLE IF NOT EXISTS `audit_log_event_types` (
  `log_event_type_id` int(5) NOT NULL auto_increment,
  `log_event_type_name` varchar(20) NOT NULL,
  `log_event_type_desc` varchar(255) NOT NULL,
  `log_event_type_enabled` int(1) NOT NULL default '1',
  PRIMARY KEY  (`log_event_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `audit_log_event_types`
--

INSERT INTO `audit_log_event_types` (`log_event_type_id`, `log_event_type_name`, `log_event_type_desc`, `log_event_type_enabled`) VALUES
(1, 'userlogin', 'User Login', 1),
(2, 'userlogout', 'User Logout', 1),
(3, 'desktopconnect', 'Connect to desktop: <-DESKTOP_NAME->(<-DESKTOP_IPADDRESS->)', 1),
(4, 'editdesktop', 'Edit desktop: <-DESKTOP_NAME->(<-DESKTOP_IPADDRESS->)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `audit_log_user`
--

CREATE TABLE IF NOT EXISTS `audit_log_user` (
  `log_entry_id` bigint(20) NOT NULL auto_increment,
  `log_entry_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `log_entry_user_name` varchar(100) NOT NULL,
  `log_entry_user_ipaddr` varchar(16) NOT NULL,
  `log_entry_info` text NOT NULL,
  PRIMARY KEY  (`log_entry_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `desktops`
--

CREATE TABLE IF NOT EXISTS `desktops` (
  `desktop_id` int(5) NOT NULL auto_increment,
  `desktop_type_id` int(1) NOT NULL,
  `desktop_name` varchar(20) NOT NULL,
  `desktop_display_name` varchar(20) default NULL,
  `desktop_description` varchar(255) default NULL,
  `desktop_folder_id` int(5) NOT NULL default '0',
  `desktop_enabled` int(1) NOT NULL default '1',
  `desktop_user_editable` int(1) NOT NULL default '1',
  `desktop_max_resolution_id` int(5) default NULL,
  `desktop_ip_address` varchar(16) default NULL,
  `operating_system_id` int(5) NOT NULL,
  `remote_protocol_id` int(2) NOT NULL,
  PRIMARY KEY  (`desktop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `desktop_entitlements`
--

CREATE TABLE IF NOT EXISTS `desktop_entitlements` (
  `entitlement_id` bigint(20) NOT NULL auto_increment,
  `desktop_id` int(5) NOT NULL,
  `user_id` int(5) default NULL,
  `group_id` int(5) default NULL,
  PRIMARY KEY  (`entitlement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `desktop_folders`
--

CREATE TABLE IF NOT EXISTS `desktop_folders` (
  `folder_id` int(5) NOT NULL auto_increment,
  `folder_name` varchar(20) NOT NULL,
  `folder_parentid` int(5) NOT NULL default '0',
  PRIMARY KEY  (`folder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `desktop_folders`
--


-- --------------------------------------------------------

--
-- Table structure for table `desktop_operating_systems`
--

CREATE TABLE IF NOT EXISTS `desktop_operating_systems` (
  `operating_system_id` int(5) NOT NULL auto_increment,
  `operating_system_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`operating_system_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `desktop_operating_systems`
--

INSERT INTO `desktop_operating_systems` (`operating_system_id`, `operating_system_name`) VALUES
(1, 'Microsoft Windows XP Professional');

-- --------------------------------------------------------

--
-- Table structure for table `desktop_remote_protocols`
--

CREATE TABLE IF NOT EXISTS `desktop_remote_protocols` (
  `remote_protocol_id` int(2) NOT NULL auto_increment,
  `remote_protocol_name` varchar(10) NOT NULL,
  `remote_protocol_defport` int(5) NOT NULL,
  `remote_protocol_minport` int(5) NOT NULL,
  `remote_protocol_maxport` int(5) NOT NULL,
  `remote_protocol_enabled` int(1) NOT NULL default '1',
  `remote_protocol_isdefault` int(1) NOT NULL default '0',
  PRIMARY KEY  (`remote_protocol_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `desktop_remote_protocols`
--

INSERT INTO `desktop_remote_protocols` (`remote_protocol_id`, `remote_protocol_name`, `remote_protocol_defport`, `remote_protocol_minport`, `remote_protocol_maxport`, `remote_protocol_enabled`, `remote_protocol_isdefault`) VALUES
(1, 'rdp', 3389, 3389, 3399, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `desktop_remote_sessions`
--

CREATE TABLE IF NOT EXISTS `desktop_remote_sessions` (
  `session_id` bigint(10) NOT NULL auto_increment,
  `session_start_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `session_end_timestamp` timestamp NULL default NULL,
  `session_src_ipaddr` varchar(16) NOT NULL,
  `session_dst_ipaddr` varchar(16) NOT NULL,
  `session_src_port` int(5) NOT NULL,
  `session_dst_port` int(5) NOT NULL,
  `session_user_name` varchar(100) NOT NULL,
  `session_is_active` int(1) NOT NULL default '1',
  `remote_protocol_id` int(2) NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `desktop_remote_sessions`
--

-- --------------------------------------------------------

--
-- Table structure for table `desktop_resolutions`
--

CREATE TABLE IF NOT EXISTS `desktop_resolutions` (
  `resolution_id` int(5) NOT NULL auto_increment,
  `resolution_size` varchar(20) NOT NULL,
  `resolution_width` int(4) NOT NULL,
  `resolution_height` int(4) NOT NULL,
  `resolution_default` int(1) NOT NULL default '0',
  `resolution_enabled` int(11) NOT NULL default '1',
  PRIMARY KEY  (`resolution_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `desktop_resolutions`
--

INSERT INTO `desktop_resolutions` (`resolution_id`, `resolution_size`, `resolution_width`, `resolution_height`, `resolution_default`, `resolution_enabled`) VALUES
(1, '640x480', 640, 480, 0, 1),
(2, '800x600', 800, 600, 0, 1),
(3, '1024x768', 1024, 768, 1, 1),
(4, '1280x1024', 1280, 1024, 0, 1),
(5, '1600x1200', 1600, 1200, 0, 1),
(6, '1280x854', 1280, 854, 0, 1),
(7, '1440x900', 1440, 900, 0, 1),
(8, '1680x1050', 1680, 1050, 0, 1),
(9, '1920x1200', 1920, 1200, 0, 1),
(10, '2560x1600', 2560, 1600, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `desktop_types`
--

CREATE TABLE IF NOT EXISTS `desktop_types` (
  `desktop_type_id` int(1) NOT NULL auto_increment,
  `desktop_type_name` varchar(20) NOT NULL,
  `desktop_type_enabled` int(11) NOT NULL default '1',
  PRIMARY KEY  (`desktop_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `desktop_types`
--

INSERT INTO `desktop_types` (`desktop_type_id`, `desktop_type_name`, `desktop_type_enabled`) VALUES
(1, 'VMWare', 1),
(2, 'Physical', 1),
(3, 'Terminal Server', 1);

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE IF NOT EXISTS `global_settings` (
  `setting_auth_type` varchar(50) NOT NULL,
  `setting_desktop_type_default` int(1) NOT NULL,
  `setting_audit_log_enabled` int(1) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`setting_auth_type`, `setting_desktop_type_default`, `setting_audit_log_enabled`) VALUES
('local', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rdpfile_parameters`
--

CREATE TABLE IF NOT EXISTS `rdpfile_parameters` (
  `rdp_parameter_id` int(5) NOT NULL auto_increment,
  `rdp_parameter_name` varchar(100) NOT NULL,
  `rdp_parameter_type` varchar(1) NOT NULL,
  `rdp_parameter_notes` text NOT NULL,
  `rdp_parameter_description` varchar(255) default NULL,
  `rdp_parameter_enabled` int(1) NOT NULL default '1',
  `rdp_parameter_user_editable` int(1) NOT NULL default '0',
  `rdp_parameter_default` varchar(255) default NULL,
  PRIMARY KEY  (`rdp_parameter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `rdpfile_parameters`
--

INSERT INTO `rdpfile_parameters` (`rdp_parameter_id`, `rdp_parameter_name`, `rdp_parameter_type`, `rdp_parameter_notes`, `rdp_parameter_description`, `rdp_parameter_enabled`, `rdp_parameter_user_editable`, `rdp_parameter_default`) VALUES
(1, 'alternate shell', 's', 'This setting determines whether a program is started automatically when you connect with RDP. ', NULL, 0, 0, NULL),
(2, 'audiomode', 'i', 'This setting determines where sounds are played:\r\n0 - Bring to this computer\r\n1 - Leave at remote computer\r\n2 - Do not play ', NULL, 1, 1, '2'),
(3, 'authentication level', 'i', '<int>', NULL, 1, 1, '0'),
(4, 'AutoReconnect Max Retries', 'i', '<int>', NULL, 0, 0, NULL),
(5, 'autoreconnection enabled', 'i', 'This setting determines whether a client computer automatically tries to reconnect after being disconnected:\r\n0 = disabled\r\n1 = enabled', NULL, 1, 1, '1'),
(6, 'BitmapCacheSize', 'i', '<int>', NULL, 0, 0, NULL),
(7, 'BitmapPersistCache16Size', 'i', '<int>', NULL, 0, 0, NULL),
(8, 'BitmapPersistCache24Size', 'i', '<int>', NULL, 0, 0, NULL),
(9, 'BitmapPersistCache32Size', 'i', '<int>', NULL, 0, 0, NULL),
(10, 'BitmapPersistCacheSize', 'i', '<int>', NULL, 0, 0, NULL),
(11, 'ClxCommandLine', 's', '<string>', NULL, 0, 0, NULL),
(12, 'compression', 'i', 'This setting determines whether data is compressed when it is transmitted to the client computer. \r\n0 = Compression is off.\r\n1 = Compression is on.', NULL, 1, 1, '1'),
(13, 'connect to console', 'i', '0 - connect to a virtual session\r\n1 - connect to the console session', NULL, 0, 0, NULL),
(14, 'Dedicated Terminal', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(15, 'Desktop Size ID', 'i', '<int>', NULL, 0, 0, NULL),
(16, 'desktopheight', 'i', 'height of session desktop in pixels ', NULL, 0, 0, NULL),
(17, 'desktopwidth', 'i', 'width of session desktop in pixels ', NULL, 0, 0, NULL),
(18, 'DevicesToRedirect', 's', '<string>', NULL, 0, 0, NULL),
(19, 'Disable CTRL+ALT+DEL', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(20, 'DisableConnectionSharing', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(21, 'displayconnectionbar', 'i', '<0 or 1>', 'Display connection bar when in full screen mode', 1, 1, '1'),
(22, 'Domain', 's', '<string>', NULL, 0, 0, NULL),
(23, 'DrivesToRedirect', 's', '<string>', NULL, 0, 0, NULL),
(24, 'Enable Mouse', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(25, 'EnableCredSspSupport', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(26, 'full address', 's', 'IP address/name of server (and optional alternate port)', NULL, 1, 0, '<-DESKTOP_IPADDRESS->'),
(27, 'GatewayCredentialsSource', 'i', '<int>', NULL, 0, 0, NULL),
(28, 'GatewayHostname', 's', '<string>', NULL, 0, 0, NULL),
(29, 'GatewayProfileUsageMethod', 'i', '<int>', NULL, 0, 0, NULL),
(30, 'GatewayUsageMethod', 'i', '<int>', NULL, 0, 0, NULL),
(31, 'keyboardhook', 'i', '0 - On the local computer\r\n1 - On the remote computer\r\n2 - In fullscreen mode only ', NULL, 1, 1, '2'),
(32, 'Negotiate Security Layer', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(33, 'Prompt For Credentials', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(34, 'RedirectClipboard', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(35, 'redirectcomports', 'i', 'set to 1, redirects client COM ports in session (XP/.NET only) \r\n<0 or 1>', NULL, 1, 1, '0'),
(36, 'redirectdrives', 'i', 'set to 1, redirects client drives in session (XP/.NET only) \r\n<0 or 1>', NULL, 1, 1, '0'),
(37, 'RedirectPOSDevices', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(38, 'redirectprinters', 'i', 'set to 1, redirects client printers in session\r\n<0 or 1>', NULL, 1, 1, '0'),
(39, 'redirectsmartcards', 'i', 'set to 1, redirects client smart cards in session (XP/.NET only)\r\n<0 or 1>', NULL, 1, 1, '1'),
(40, 'RemoteApplicationCmdLine', 's', '<string>', NULL, 0, 0, NULL),
(41, 'RemoteApplicationExpandWorkingdir', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(42, 'RemoteApplicationFile', 's', '<string>', NULL, 0, 0, NULL),
(43, 'RemoteApplicationGuid', 's', '<string>', NULL, 0, 0, NULL),
(44, 'RemoteApplicationIcon', 's', '<string>', NULL, 0, 0, NULL),
(45, 'RemoteApplicationMode', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(46, 'RemoteApplicationName', 's', '<string>', NULL, 0, 0, NULL),
(47, 'screen mode id', 'i', 'FullScreen vs. normal\r\n0 - ?\r\n1 - windowed\r\n2 - fullscreen ', NULL, 1, 1, '2'),
(48, 'server port', 'i', 'You can specify the port separately from the "full address" parameter. ', NULL, 1, 0, '<-CONNECT_PORT->'),
(49, 'session bpp', 'i', 'bit depth for session - 8, 16, or 24. Only 8 is valid for Windows 2000 Terminal Servers ', NULL, 1, 1, '16'),
(50, 'Shadow Bitmap Enabled', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(51, 'shell working directory', 's', 'Working directory if an alternate shell was specified. ', NULL, 0, 0, NULL),
(52, 'smart sizing', 'i', 'Scale the client window display of desktop when resizing\r\n0 or not present - Do not scale\r\n1 - Scale (Takes extra resources to scale) ', NULL, 0, 0, NULL),
(53, 'Span Monitors', 'i', '<0 or 1>', NULL, 0, 0, NULL),
(54, 'Transport Type', 'i', '<int>', NULL, 0, 0, NULL),
(55, 'username', 's', 'name of user logging on ', NULL, 1, 0, '<-USER_NAME->'),
(56, 'WinPosStr', 's', '<string>', NULL, 0, 0, NULL),
(57, 'auto connect', 'i', '0 or 1 ', NULL, 0, 0, NULL),
(58, 'bitmapcachepersistenable', 'i', '1 maintains bitmap cache between sessions', NULL, 1, 1, '1'),
(59, 'disable full window drag', 'i', 'set to 1, disables display of window contents while dragging in session ', NULL, 1, 1, '1'),
(60, 'disable menu anims', 'i', 'set to 1, disables menu animations in session ', NULL, 1, 1, '1'),
(61, 'disable themes', 'i', 'set to 1, disables use of themes in session ', NULL, 1, 1, '0'),
(62, 'disable wallpaper', 'i', 'set to 1, disables display of wallpaper in session ', NULL, 1, 1, '1'),
(63, 'disable cursor setting', 'i', '<0 or 1>', NULL, 1, 1, '0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(5) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(255) default NULL,
  `user_uid` varchar(255) default NULL,
  `user_enabled` int(1) NOT NULL default '1',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`, `user_uid`, `user_enabled`) VALUES
(1, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups_members`
--

CREATE TABLE IF NOT EXISTS `users_groups_members` (
  `user_id` int(5) NOT NULL,
  `group_id` int(5) NOT NULL,
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_groups_members`
--

INSERT INTO `users_groups_members` (`user_id`, `group_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `group_id` int(5) NOT NULL auto_increment,
  `group_name` varchar(50) NOT NULL,
  `group_gid` varchar(255) default NULL,
  `group_enabled` int(1) NOT NULL default '1',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`group_id`, `group_name`, `group_gid`, `group_enabled`) VALUES
(1, 'Test Group', NULL, 1),
(2, 'Test Group2', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_rdp_settings`
--

CREATE TABLE IF NOT EXISTS `user_rdp_settings` (
  `user_id` int(5) NOT NULL,
  `desktop_id` int(5) NOT NULL,
  `rdp_parameter_id` int(5) NOT NULL,
  `rdp_parameter_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`user_id`,`desktop_id`,`rdp_parameter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_rdp_settings`
--

-- --------------------------------------------------------

--
-- Table structure for table `vcenter_datacenters`
--

CREATE TABLE IF NOT EXISTS `vcenter_datacenters` (
  `datacenter_id` int(5) NOT NULL auto_increment,
  `vcenter_id` int(5) NOT NULL,
  `datacenter_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`datacenter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vcenter_datacenters`
--

-- --------------------------------------------------------

--
-- Table structure for table `vcenter_datastores`
--

CREATE TABLE IF NOT EXISTS `vcenter_datastores` (
  `datastore_id` int(5) NOT NULL auto_increment,
  `vcenter_id` int(5) NOT NULL,
  `host_id` int(5) NOT NULL,
  `datastore_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`datastore_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vcenter_datastores`
--

-- --------------------------------------------------------

--
-- Table structure for table `vcenter_desktops`
--

CREATE TABLE IF NOT EXISTS `vcenter_desktops` (
  `desktop_id` int(5) NOT NULL,
  `vcenter_id` int(5) NOT NULL,
  `datacenter_id` int(5) NOT NULL,
  `host_id` int(5) NOT NULL,
  `datastore_id` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vcenter_desktops`
--

-- --------------------------------------------------------

--
-- Table structure for table `vcenter_hosts`
--

CREATE TABLE IF NOT EXISTS `vcenter_hosts` (
  `host_id` int(5) NOT NULL auto_increment,
  `vcenter_id` int(5) NOT NULL,
  `datacenter_id` int(5) NOT NULL,
  `host_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`host_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vcenter_hosts`
--

-- --------------------------------------------------------

--
-- Table structure for table `vcenter_servers`
--

CREATE TABLE IF NOT EXISTS `vcenter_servers` (
  `vcenter_id` int(5) NOT NULL auto_increment,
  `vcenter_name` varchar(20) NOT NULL,
  `vcenter_ipaddress` varchar(16) NOT NULL,
  `vcenter_username` varchar(50) NOT NULL,
  `vcenter_password` varchar(50) NOT NULL,
  `vcenter_protocol` varchar(5) NOT NULL,
  `vcenter_portnumber` int(5) NOT NULL,
  PRIMARY KEY  (`vcenter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vcenter_servers`
--

