<?php
header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
$final = array('status' => 'failed', 'amount' => null);
if(isset($_REQUEST['s']) === false){
	$final['status'] = "err_parameters";
	echo json_encode($final);
	die();
}
if(isset($_SESSION['email']) === false){
	$final['status'] = 'err_not_logged';
}else{
	$final['status'] = 'success';
	$id = (int)$_SESSION['id'];
	
	$symbol = mysql_real_escape_string(html_escape($_REQUEST['s']));
	
	// get stocks
		$sql = "SELECT COUNT(`user_id`) AS `instances`, COALESCE(SUM(`amount`),0) AS `amount` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}'";
            
    $users = mysql_query($sql);
    
    $idk = mysql_fetch_assoc($users);
	$final['amount'] = $idk['amount'];
}
echo json_encode($final);
?>