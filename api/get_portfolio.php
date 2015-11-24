<?php
header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
$final = array('status' => 'failed', 'stocks' => null);

if(isset($_SESSION['email']) === false){
	$final['status'] = 'err_not_logged';
}else{
	$final['status'] = 'success';
	$id = (int)$_SESSION['id'];
	
	
	// get stocks
	$sql = "SELECT 
                `stock_symbol` AS `stock`,
                `amount` AS `amount`,
                `stock_name` AS `name`,
                `stock_price` AS `price`,
                null AS `operation`
           FROM `user_stocks`
           WHERE `user_id` = '{$id}'
           UNION ALL
           SELECT `stock_symbol` AS `symbol`, `amount`, `stock_name` AS `name`, `stock_price` AS `price`, `operation` FROM `stock_queue` WHERE `user_id` = '{$id}'";
            
    $users = mysql_query($sql);
    
    $rows = array();
    while (($row = mysql_fetch_assoc($users)) !== false){
        $rows[] = array(
            'stock'		=> strtoupper($row['stock']),
            'amount'	=> $row['amount'],		
            'name'		=> $row['name'],
            'price'     => (empty($row['price']) ? null : $row['price']),
            'operation' => (empty($row['operation']) ? null : $row['operation']),
            'type' => (empty($row['operation']) ? "S" : "P")
            
        );
    }
	$final['stocks'] = $rows;
	
	// get user info
    $stuff = mysql_query("SELECT `user_value` AS `value` FROM `users` WHERE `user_id` = '{$id}'");
	$final['user'] = mysql_fetch_assoc($stuff);
}
echo json_encode($final);
?>