<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("log_errors", 1);
if(php_sapi_name() === 'apache2handler'){ die(); }
$path = dirname(__FILE__);
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
ob_start();
include("{$path}/inc/stocks.inc.php");
clear_queue();
echo ob_get_contents();
?>