<?php
die();
include('../core/init.inc.php');

// something that I hate about this system is how we are supposed to store passwords
// this should be *okay*
// I can only cross my fingers
$errors = array();
if(is_finished($_SESSION['tid']) && !has_download($_SESSION['tid'])){
	header("Location: builder?claimed");
	die();
}elseif(!is_finished($_SESSION['tid']) || !has_download($_SESSION['tid'])){
	die("Download link not available or account not finished.");
}else{
	$id = (int)$_SESSION['tid'];
	$filename = md5($id);
	header("Content-type:application/pdf");
	echo file_get_contents("/var/www/core/tex/{$filename}.pdf");
	unlink("/var/www/core/tex/{$filename}.pdf");
	unlink("/var/www/core/tex/{$filename}.log");
	unlink("/var/www/core/tex/{$filename}.tex");
	unlink("/var/www/core/tex/{$filename}.aux");
}

?>