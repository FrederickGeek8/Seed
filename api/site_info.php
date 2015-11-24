<?php
header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
$final = array('status' => 'failed', 'site' => null);

if(isset($_SESSION['email']) === false){
	$final['status'] = 'err_not_logged';
}else{
	include('../core/inc/sites.inc.php');
	$final['status'] = 'success';
	$final['site'] = array($sites[$_SESSION["cid"]][1], $sites[$_SESSION["cid"]][2], 	$sites[$_SESSION["cid"]][3]);
}
echo json_encode($final);
?>