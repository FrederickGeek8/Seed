<?php
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');

mysql_query("DELETE FROM `users` WHERE `user_id` = '0'");
mysql_query("DELETE FROM `stocks` WHERE `user_id` = '0'");
?>