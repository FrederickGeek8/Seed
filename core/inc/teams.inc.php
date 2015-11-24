<?php
require_once "Mail.php";

function email_exists($email){
	$email = mysql_real_escape_string(html_escape($email));
	$total = mysql_query("SELECT COUNT(`team_id`) FROM `teams` WHERE `team_email` = '{$email}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function activate_account($aid){
    $aid = mysql_real_escape_string($aid);
    
    mysql_query("DELETE FROM `team_activations` WHERE `activation_code` = '{$aid}'");
}

function is_active($email){
    $email = mysql_real_escape_string(html_escape($email));
    
    $sql = "SELECT
                COUNT(`team_activations`.`team_id`)
            FROM `teams`
            INNER JOIN `team_activations`
            ON `teams`.`team_id` = `team_activations`.`team_id`
            WHERE `teams`.`team_email` = '{$email}'";
            
    $result = mysql_query($sql);
    
    return (mysql_result($result, 0) == '0') ? true : false;
}

function add_manager($comp, $school, $email, $pass){
    global $siteinfo;
    $school   = mysql_real_escape_string(html_escape($school));
    $email  = mysql_real_escape_string(html_escape($email));
    $pass   = crypt(urlencode($pass));
    
    $charset = array_flip(array_merge(range('a' ,'z'), range('A', 'Z'), range('0', '9')));
    $aid = implode('', array_rand($charset, 10));

    $mime_boundary = md5(date('r', time()));
    
    $to = filter_var($email, FILTER_SANITIZE_EMAIL);
    $teamname = "noreply@marketdream.org";
    $from = "{$siteinfo['title']} <{$teamname}>";
    $subject = "Your new account at {$siteinfo['title']}!";
    $body = <<<EMAIL
--$mime_boundary
Content-Type: text/plain; charset=us-ascii
Content-Transfer-Encoding: 7bit

    Hello,\nThanks for registering a team manager account at {$siteinfo['title']}. Before you login you must activate your account.\n\nTo do that, simply click the button below.\nActivate Account (https://marketdream.org/activate.php?aid={$aid})\n\nNote: Student account registration opens the 8th of September and closes September 30th. This means that you will not be able to login into this account till then.

--$mime_boundary
Content-Type: text/html; charset=us-ascii
Content-Transfer-Encoding: 7bit

    <!DOCTYPE html>
<html lang="en" id="top" style="margin-bottom: 0; margin-right: 0; margin-top: 0; margin-left: 0; padding-right: 0; padding-bottom: 0; padding-top: 0; padding-left: 0; text-rendering: optimizelegibility; font-smooth: always; -webkit-font-smoothing: antialiased; text-shadow: 1px 1px 1px rgba(0,0,0,0.004);">
    <head>
        <meta charset="utf-8">
        <title>{$siteinfo['title']}</title>
    </head>
    <body style='background:lightgrey;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;text-align:center;'>
      <h1 style="margin-top: 35px; color: #212121; text-align: center; font-size: 6em; position: relative;margin:0;font-weight: 100; line-height: 1.1;">{$siteinfo['title']}</h1>
    <p>Hello,<br>Thanks for registering a team manager account at {$siteinfo['title']}. Before you login you must activate your account.<br><br>To do that, simply click the button below.</p>
<a href="https://marketdream.org/activate.php?aid={$aid}" style='display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent;color: #fff; background-color: #d9534f; border-color: #d43f3a;padding: 10px 45px; font-size: 18px; line-height: 1.33; border-radius: 6px;text-decoration: none;'>Activate Account</a>
        <p><br><br><small>Note: Student account registration opens the 8th of September and closes September 30th. This means that you will not be able to login into this account till then.</small></p>
      <style type="text/css">body{background:lightgrey;font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;text-align:center;} h1 {margin-top: 35px; color: #212121; text-align: center; font-size: 6em; position: relative;margin:0;font-weight: 100; line-height: 1.1;} a[href]{display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent;color: #fff; background-color: #d9534f; border-color: #d43f3a;padding: 10px 45px; font-size: 18px; line-height: 1.33; border-radius: 6px;text-decoration: none;}</style>
    </body>
</html>

--$mime_boundary--
EMAIL;
    $host = "smtpout.secureserver.net";
    $port = "3535";
    $password = "password";
 
    $headers = array ('From' => $from,
   'To' => $to,
   'Reply-to' => $from,
   'Message-ID' => "<" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">",
   'MIME-Version' => '1.0',
   'Date' => date(DATE_RFC2822),
   'Subject' => $subject,
   'Content-Type' => "multipart/alternative;\n boundary=" . $mime_boundary);
    $smtp = Mail::factory('smtp',
            array ('host' => $host,
                'port' => $port,
                'auth' => true,
                'username' => $teamname,
                'password' => $password));
 
 $mail = $smtp->send($email, $headers, $body);
        

        mysql_query("INSERT INTO `teams` (`comp_id`, `team_school`, `team_password`, `team_email`) VALUES ('{$comp}', '{$school}', '{$pass}', '{$email}')");
        
        $team_id = mysql_insert_id();
        
        mysql_query("INSERT INTO `team_activations` (`team_id`, `activation_code`) VALUES ('{$team_id}', '{$aid}')");
}

function school_exists($school){
	$school = mysql_real_escape_string(html_escape($school));
	$sql = mysql_query("SELECT COUNT(`team_id`) FROM `teams` WHERE `team_school` = '{$school}'");
	return (mysql_result($sql, 0) == '1') ? true : false;
}

function get_team_id($email){
    $email = mysql_real_escape_string(html_escape($email));
    $sql = "SELECT `team_id` AS `id` FROM `teams` WHERE `team_email` = '{$email}'";
    
    $id = mysql_query($sql);
    
    return mysql_result($id, 0);
}

function valid_team_credentials($email, $pass){
    $email = mysql_real_escape_string(html_escape(strtolower($email)));

    $total = mysql_query("SELECT COUNT(`team_id`) FROM `teams` WHERE `team_email` = '{$email}'");
    if(mysql_result($total, 0) == '1'){
        $cred = mysql_query("SELECT `team_email`, `team_password` FROM `teams` WHERE `team_email` = '{$email}'");
        $cred = mysql_fetch_assoc($cred);
        if($email != strtolower($cred['team_email']) || crypt(urlencode($pass), $cred['team_password']) != $cred['team_password']){
            return false;
        }else{
            return true;
        }
    }else{
        return false;
    }

}

function get_members($tid){
    $tid = (int)$tid;

    $sql = mysql_query("SELECT `user_school` FROM `users` WHERE `team_id` = '{$tid}'");
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = $row['user_school'];
    }

    return $rows;
}

function add_users($comp, $tid, $num, $ex){
    $tid = (int)$tid;
    $num = (int)$num;
    $ex = (int)$ex;

    //get school
    $user = mysql_query("SELECT `team_school` FROM `teams` WHERE `team_id` = '{$tid}'");
    $user = mysql_result($user, 0);
    $username = explode(' ',trim($user))[0];

    for ($i=1;$i<=$num;$i++) {
        // generate username
        $username2 = $username . "-" .    ($ex + $i);
        $password = bin2hex(openssl_random_pseudo_bytes(5));
        $school = $user . " Team " . ($ex + $i);

        mysql_query("INSERT INTO `users` (`comp_id`, `team_id`, `user_name`, `user_school`, `user_password`) VALUES ('{$comp}', '{$tid}', '{$username2}', '{$school}', '{$password}')");
    }
}

function finish_account($tid){
    $tid = (int)$tid;

    // and this is where things started to get scary
    // see what were going to do is get all the user information, create a pdf, post the pdf details to database, and then, to top everything off, we are going to have to encrypt the passwords
    // isn't coding fun!!!

    // this is the part where we get infomation
    $sql = mysql_query("SELECT `user_id` AS `id`, `user_school` AS `school`, `user_name` AS `name`, `user_password` AS `password` FROM `users` WHERE `team_id` = '{$tid}'");
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
         $rows[] = array(
            'id'     => $row['id'],
            'name' => $row['name'],
            'school'  => $row['school'],
            'password' => $row['password']
        );
    }

    // now that we've gotten the information, we need to generate a PDF spreedsheet
    $code = "";
    foreach ($rows as $student) {
        $code .= "{$student['school']} & {$student['name']} & {$student['password']} \\\\ \hline \n";

        // while we're here we might as well just hash things
        // isnt it awesomet that I have to put comments in my code from keeping me from going crazy
        // it is actually making me more happy... to anyone that is reading this code you should practice this, not for documentation purposes but because it will help a lot with your mental heath
        $crypted = crypt(urlencode($student['password']));
        mysql_query("UPDATE `users` SET `user_password` = '{$crypted}' WHERE `user_id` = '{$student['id']}'");
    }

    // before we finish everything up, lets make it a little more personlized
    $school = mysql_query("SELECT `team_school` FROM `teams` WHERE `team_id` = '{$tid}'");
    $school = mysql_result($school,0);

    $final = "\documentclass{article}
\usepackage[english]{babel}
\usepackage{tabularx}
\usepackage{array}
\setlength{\\tabcolsep}{20pt}

\begin{document}

\begin{center}
    \\title tCAIC Credentials for {$school} \\\\[1\baselineskip]
    \begin{tabular}{ | c | c | c |}
    \hline
    \\textbf{Full Name} & \\textbf{Username} & \\textbf{Password} \\\\ \hline
    {$code}
    \\end{tabular}
    \\\\[1\baselineskip]
    Keep this file safe! This will not appear again!
\\end{center}

\\end{document}";

    // put the contents
    $filename = md5($tid);
    if(file_exists("/var/www/core/tex/{$filename}.tex")){
        die("Error. Please report to admins.<br>Error code:Er4425");
    }

    file_put_contents("/var/www/core/tex/{$filename}.tex", $final);
    exec("cd /var/www/core/tex/;pdflatex {$filename}.tex");

    mysql_query("UPDATE `teams` SET finished = '1' WHERE `team_id` = '{$tid}'");
}

function is_finished($tid){
    $tid = (int)$tid;

    $total = mysql_query("SELECT COUNT(`team_id`) FROM `teams` WHERE `team_id` = '{$tid}' AND `finished` = '1'");
    if(mysql_result($total, 0) == '1'){
        return true;
    }else{
        return false;
    }
}

function has_download($tid){
    $tid = (int)$tid;

    $filename = md5($tid);
    if(file_exists("/var/www/core/tex/{$filename}.tex")){
        return true;
    }else{
        return false;
    }
}

function change_team_password($id, $password){
    $id = (int)$id;
    $password = crypt(urlencode($password));
    mysql_query("UPDATE `teams` SET `team_password` = '{$password}' WHERE `team_id` = '{$id}'");
}

function reset_team_password($email){
    global $siteinfo;
    $email = mysql_real_escape_string(html_escape($email));
    $user_id = (int)get_team_id($email);

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
    <a href='http://marketdream.org/team/forgot_password?id={$reset_id}'>http://marketdream.org/team/forgot_password?id={$reset_id}</a><br />
    <br />
    If you <b>did not</b> request a new password, please follow <a href='http://marketdream.org/team/forgot_password?fake={$reset_id}'>this link</a>.

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
    mysql_query("INSERT INTO `reset_codes` (`user_id`, `reset_code`) VALUES ('{$user_id}', '{$reset_id}')");
}

?>