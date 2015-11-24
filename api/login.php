<?php

header('Content-Type: application/json');
include("init.inc.php");

function get_id($email){
    $email = mysql_real_escape_string(html_escape($email));
    $sql = "SELECT `user_id` AS `id`, `comp_id` AS `cid` FROM `users` WHERE `user_name` = '{$email}'";
    
    $id = mysql_query($sql);
    
    return mysql_fetch_assoc($id);
}

$status = array('status' => 'failed', 'site' => null);

if(isset($_REQUEST['u'], $_REQUEST['p']) === false){
	$status['status'] = "err_parameters";
	echo json_encode($status);
	die();
}

if(isset($_SESSION['email'])){
	$status['status'] = 'failed_already_login';
	echo json_encode($status);
	die();
}

if(valid_credentials(urldecode($_REQUEST['u']), urldecode($_REQUEST['p']))){
	$status['status'] = 'success';
	// $status['code'] = session_id();
	$_SESSION['email'] = html_escape(urldecode($_REQUEST['u']));
	$id = get_id($_SESSION['email']);
	$_SESSION['id'] = $id["id"];
	$_SESSION['cid'] = $id["cid"];
	$status['site'] = array($sites[$id["cid"]][1], $sites[$id["cid"]][2]);
	echo json_encode($status);
	die();
}else{
	$status['status'] = 'failed_wrong_creds';
	echo json_encode($status);
	die();
}

?>