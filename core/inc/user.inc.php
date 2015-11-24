<?php
require_once "Mail.php";

function valid_uid($id){
    $id = (int)$id;
    $total = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = '{$id}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function user_exists($name){
    $name = mysql_real_escape_string(html_escape($name));

    $total = mysql_query("SELECT COUNT(`user_name`) FROM `users` WHERE `user_name` = '{$name}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function get_users(){
    $sql = "SELECT `user_id` AS `id`, `user_name` AS `name` FROM `users`";

    $sql = mysql_query($sql);
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'id'     => $row['id'],
            'name'	=> $row['name']
        );
    }

    return $rows;
}

function valid_reset($id){
    $id = mysql_real_escape_string(html_escape($id));
    $total = mysql_query("SELECT COUNT(`user_id`) FROM `reset_codes` WHERE `reset_code` = '{$id}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function change_password($id, $password){
    $id = (int)$id;
    $password = crypt(urlencode($password));

    mysql_query("UPDATE `users` SET `user_password` = '{$password}' WHERE `user_id` = '{$id}'");
}

function get_user_contact($name){
    $name = mysql_real_escape_string(html_escape($name));

    $sql = mysql_query("SELECT `team_id` FROM `users` WHERE `user_name` = '{$name}'");

    $tid = mysql_result($sql, 0);

    $sql = mysql_query("SELECT `team_email` FROM `teams` WHERE `team_id` = '{$tid}'");

    return mysql_result($sql, 0);

    // Return team email
}

function reset_password($studentid, $email){
    global $siteinfo;
    $email = mysql_real_escape_string(html_escape($email));

    $charset = array_flip(array_merge(range('a' ,'z'), range('A', 'Z'), range('0', '9')));
    $reset_id = implode('', array_rand($charset, 15));

    $to = $email;
    $username = "noreply@marketdream.org";
    $from = "{$siteinfo['title']} <{$username}>";
    $subject = "Password Reset at {$siteinfo['title']}";
    $body = <<<EMAIL
    <html>

    Hello,<br />
    <br />
    A password reset request has been sent from {$siteinfo['title']}. To reset your password go to the link below.<br />
    <a href='http://marketdream.org/forgot_password?id={$reset_id}'>http://marketdream.org/forgot_password?id={$reset_id}</a><br />
    <br />
    If you <b>did not</b> request a new password, please follow <a href='http://marketdream.org/forgot_password?fake={$reset_id}'>this link</a>.

    </html>
EMAIL;
    $host = "smtpout.secureserver.net";
    $port = "3535";
    $password = "password";

    $headers = array ('From' => $from,
   'To' => $to,
   'Reply-to' => $from,
   'Message-ID' => "<" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">",
   'MIME-Version: 1.0',
   'Date' => date(DATE_RFC2822),
   'Subject' => $subject,
   'Content-type' => "text/html; charset=iso-8859-1");
    $smtp = Mail::factory('smtp',
            array ('host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password));

    $mail = $smtp->send($email, $headers, $body);
    mysql_query("INSERT INTO `reset_codes` (`user_id`, `reset_code`) VALUES ('{$studentid}', '{$reset_id}')");
}

function valid_credentials($name, $pass){
    $name = mysql_real_escape_string(html_escape(strtolower($name)));

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_name` = '{$name}'");
    if(mysql_result($total, 0) == '1'){
        $cred = mysql_query("SELECT `user_name`, `user_password` FROM `users` WHERE `user_name` = '{$name}'");
        $cred = mysql_fetch_assoc($cred);
        if($name != strtolower($cred['user_name']) || crypt(urlencode($pass), $cred['user_password']) != $cred['user_password']){
            return false;
        }else{
            return true;
        }
    }else{
        return false;
    }

}

function add_user($school, $email, $pass){
    global $siteinfo;
    $school   = mysql_real_escape_string(html_escape($school));
    $email  = mysql_real_escape_string(html_escape($email));
    $pass   = crypt(urlencode($pass));

    $charset = array_flip(array_merge(range('a' ,'z'), range('A', 'Z'), range('0', '9')));
    $aid = implode('', array_rand($charset, 10));

    $to = $email;
    $username = "noreply@marketdream.org";
    $from = "{$siteinfo['title']} <{$username}>";
    $subject = "Your new account at {$siteinfo['title']}!";
    $body = <<<EMAIL
    <html>

    Hi,<br />
    <br />
    Thanks for registering at {$siteinfo['title']}. Before you login you need to activate your account.<br />
    <br />
    To do that simple click the link below.<br />
    <br />
    <a href='http://marketdream.org/activate.php?aid={$aid}'>http://marketdream.org/activate.php?aid={$aid}</a>

    </html>
EMAIL;
    $host = "smtpout.secureserver.net";
    $port = "3535";
    $password = "password";

    $headers = array ('From' => $from,
   'To' => $to,
   'Reply-to' => $from,
   'Message-ID' => "<" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">",
   'MIME-Version: 1.0',
   'Date' => date(DATE_RFC2822),
   'Subject' => $subject,
   'Content-type' => "text/html; charset=iso-8859-1");
    $smtp = Mail::factory('smtp',
            array ('host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $username,
                'password' => $password));

 $mail = $smtp->send($email, $headers, $body);


        mysql_query("INSERT INTO `users` (`user_school`, `user_password`, `user_email`) VALUES ('{$school}', '{$pass}', '{$email}')");

        $user_id = mysql_insert_id();

        mysql_query("INSERT INTO `user_activations` (`user_id`, `activation_code`) VALUES ('{$user_id}', '{$aid}')");
}

function get_user_id($name){
    $name = mysql_real_escape_string(html_escape($name));
    $sql = "SELECT `user_id` AS `id` FROM `users` WHERE `user_name` = '{$name}'";

    $id = mysql_query($sql);

    return mysql_result($id, 0);
}

function get_leaderboard(){
    $sql = "SELECT
                `user_id` AS `id`,
                `user_school` AS `name`,
                `portfolio_value` AS `value`
            FROM `users`
            WHERE `hidden` = '0' AND `portfolio_value` != '100000'
            ORDER BY `portfolio_value` DESC
            LIMIT 0 , 10";

    $sql = mysql_query($sql);
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'id'     => $row['id'],
            'name' => $row['name'],
            'value'  => $row['value']
        );
    }

    return $rows;
}

function user_lookup($uid){
    $uid = (int)$uid;

    $sql = "SELECT
                `user_id` AS `id`,
                `user_school` AS `school`,
                `user_password` AS `password`,
                `user_value` AS `value`
            FROM `users`
            WHERE `user_id` = '{$uid}'";

    $stuff = mysql_query($sql);
    $stuff = mysql_fetch_assoc($stuff);

    return $stuff;
}

function get_alias($id){
    $id = (int)$id;
    $sql = "SELECT
                `alias`
            FROM `admins`
            WHERE `user_id` = '{$id}'
            UNION ALL
            SELECT
                `alias`
            FROM `writers`
            WHERE `user_id` = '{$id}'";
    $sql = mysql_query($sql);
    return mysql_result($sql, 0);
}

function get_user_cash($id){
    $id = (int)$id;
    $sql = mysql_query("SELECT `user_value` FROM `users` WHERE `user_id` = '{$id}'");
    return mysql_result($sql, 0);
}

?>
