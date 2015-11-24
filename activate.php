<?php
session_start();
$_SESSION = array();
session_destroy();
session_start();

mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');

include("core/inc/teams.inc.php");

if (isset($_GET['aid'])){
    activate_account($_GET['aid']);
}

header('Location: /?active');
die();

?>
