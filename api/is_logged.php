<?php
header('Content-Type: text/plain');
session_start();
if(isset($_SESSION['email'])){
	echo 1;
	die();
}else{
	echo 0;
	die();
}
?>