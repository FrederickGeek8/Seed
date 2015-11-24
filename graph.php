<?php

include('core/init.inc.php');

$users = get_users();

foreach($users AS $user){

if(is_dir("/var/www/core/history/".$user['id']) === false){
                    mkdir("/var/www/core/history/".$user['id']);
                }
                if(file_exists("/var/www/core/history/".$user['id']."/history") === false){
                    touch("/var/www/core/history/".$user['id']."/history");
                }

}
?>
