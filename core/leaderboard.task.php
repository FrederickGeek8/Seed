<?php
if(php_sapi_name() === 'apache2handler'){ die(); }
$path = dirname(__FILE__);
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
include("{$path}/inc/stocks.inc.php");
if(date("Hi", time()) == 931){
	update_leaderboard(true);
}else{
	update_leaderboard();
}
?>
