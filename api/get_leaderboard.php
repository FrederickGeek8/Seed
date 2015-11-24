<?php
header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
$final = array('status' => 'failed', 'leaders' => null);

if(isset($_SESSION['email']) === false){
	$final['status'] = 'err_not_logged';
}else{
	$sql = "SELECT
                `user_school` AS `name`,
                `portfolio_value` AS `value`
            FROM `users`
            ORDER BY `portfolio_value` DESC
            LIMIT 0 , 10";

    $sql = mysql_query($sql);
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array($row['name'],$row['value']
        );
    }
	$final['status'] = 'success';
    $final['leaders'] = $rows;
}
echo json_encode($final);
?>