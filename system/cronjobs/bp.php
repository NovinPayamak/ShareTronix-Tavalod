<?php
	
	chdir(dirname(__FILE__));
	if( !isset($_SERVER['HTTP_HOST']) ) { $_SERVER['HTTP_HOST'] = '127.0.0.1'; }
	if( !isset($_SERVER['REQUEST_URI']) ) { $_SERVER['REQUEST_URI'] = '/'; }
	if( !isset($_SERVER['REMOTE_ADDR']) ) { $_SERVER['REMOTE_ADDR'] = '127.0.0.1'; }
	
	require_once('../helpers/func_main.php');
	
	require_once('../conf_system.php');
	require_once($C->INCPATH.'pdate.php');
	$_SERVER['HTTP_HOST']	= $C->OUTSIDE_DOMAIN;
	$_SERVER['REQUEST_URI'] = $C->OUTSIDE_SITE_URL;
	$C->DOMAIN			= $C->OUTSIDE_DOMAIN;
	
	@session_start();
	
	if( !isset($cache) ) { $cache = new cache(); }
	if( !isset($db1) ) { $db1 = new mysql($C->DB_HOST, $C->DB_USER, $C->DB_PASS, $C->DB_NAME); }
	if( !isset($db2) ) { $db2 = & $db1; }
	if( !isset($network)) {
		$network	= new network();
		$network->LOAD();
	}
	$user		= new user();
	$page		= new page();
	
	ini_set( 'error_reporting', E_ALL | E_STRICT );
	ini_set( 'display_errors', '1' );
	ini_set( 'max_execution_time',	10*60 );
	ini_set( 'memory_limit',	64*1024*1024 );
	

	
	$r = $db2->query('SELECT id,mobile_sms FROM users WHERE `birthdate` LIKE "%'.pdate('m-d').'" ORDER BY id DESC');
	
	while($o = $db2->fetch_object($r)){
	$num = decode_mobile_num($o->mobile_sms);
	
	if((is_numeric($num)) && ($u = $network->get_user_by_id($o->id))){
	echo SEND_SMS($num,$u->username."\n عزیز زادروزتان مبارک \n".$C->SITE_TITLE);
	}
	}
	
	exit;
?>