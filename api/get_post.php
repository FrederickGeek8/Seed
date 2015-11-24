<?php

header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');

$final = array('status' => 'failed', 'data' => null);

if(!isset($_SESSION['email'])){
	$final['status'] = 'err_not_logged';
}elseif(!isset($_GET['i'])){
	$final['status'] = 'err_invalid_post';
}else{
	$id = (int)$_GET['i'];
	
	// validate post
	$id = (int)$id;
    $total = mysql_query("SELECT COUNT(`post_id`) FROM `posts` WHERE `post_id` = '{$id}'");
    if(mysql_result($total, 0) == '1'){
    
	    // get post
		$sql = "SELECT
	                `user_name` AS `user`,
	                `post_title` AS `title`,
	                `post_body` AS `body`
	            FROM `posts`
	            WHERE `post_id` = '{$id}'";
	    $sql = mysql_query($sql);
		$final['data'] = mysql_fetch_assoc($sql);
		$final['status'] = 'success';
	
	}else{
		$final['status'] = 'err_invalid_post';
	}
}

echo json_encode($final);

?>