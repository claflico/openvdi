<?php

  // remove slashes added by magic quotes if enabled
  function stripData($data) {
	return ini_get('magic_quotes_gpc') ? stripslashes($data) : $data;
  }

  // as above + convert HTML chars to HTML
  function htmlChars($data) {
	return htmlspecialchars(stripData($data), ENT_QUOTES);
  }

  // add slashes if magic quotes disabled
  function prepareData($data) {
	return ini_get('magic_quotes_gpc') ? $data : addslashes($data);
  }

  // prepare data for comparison to posted data
  function compData($data) {
	return ini_get('magic_quotes_gpc') ? addslashes($data) : $data;
  }

  // return true if emai matches valid email syntax
  function isUsername($username) {
	return preg_match("/^[a-z0-9]+$/", $username);
  }

  // return true if email matches valid email syntax
  function isEmail($email) {
	return preg_match("/^(((([^]<>()[\.,;:@\" ]|(\\\[\\x00-\\x7F]))\\.?)+)|(\"((\\\[\\x00-\\x7F])|[^\\x0D\\x0A\"\\\])+\"))@((([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))(\\.([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))*|(#[[:digit:]]+)|(\\[([[:digit:]]{1,3}(\\.[[:digit:]]{1,3}){3})]))$/", $email);
  }

  function isColor($color) {
	return preg_match("/^[A-Za-z]+|(#[A-Fa-f0-9]{6})$/", $color);
  }

  function getLink($sortby, $sortdir, $filteron, $filterstr, $page='') {
	$link = $_SERVER['PHP_SELF'] . '?';
	$link .= $page == '' ? '' : "PAGE=$page";
	$link .= "&SORT_BY=$sortby&SORT_DIR=$sortdir&FILTER_ON=$filteron&FILTER_STR=" . urlencode(stripData($filterstr));
	return $link;
  }



	
?>