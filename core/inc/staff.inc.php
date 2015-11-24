<?php

function add_writer($id){
    $id = (int)$id;
    mysql_query("INSERT INTO `writer` (`user_id`) VALUES ('{$id}')");
}

function add_admin($id){
    $id = (int)$id;
    mysql_query("INSERT INTO `admins` (`user_id`) VALUES ('{$id}')");
}

function is_writer($id){
	$id = (int)$id;
	$total = mysql_query("SELECT (SELECT COUNT(`user_id`) FROM `writers` WHERE `user_id` = '{$id}') + (SELECT COUNT(`user_id`) FROM `admins` WHERE `user_id` = '{$id}')");
	return (mysql_result($total, 0) == '1' || mysql_result($total, 0) == '2') ? true : false;
}

function is_admin($id){
	$id = (int)$id;
	$total = mysql_query("SELECT COUNT(`user_id`) FROM `admins` WHERE `user_id` = '{$id}'");
	return (mysql_result($total, 0) == '1') ? true : false;
}

function report_bug($email, $report){
	$email = mysql_real_escape_string(html_escape($email));
	$report = mysql_real_escape_string(nl2br(html_escape($report)));
	mysql_query("INSERT INTO `bug_reports` (`report_email`, `report_body`) VALUES ('{$email}', '{$report}')");
	$infohash = sha1(mysql_insert_id());
	$to = 'FrederickGeek8@gmail.com';
    $username = "bugreport@marketdream.org";
    $from = "Bugbot <{$username}>";
    $subject = "New Bug Report";
    $body = <<<EMAIL
    <html>
        A new bug report had been published. Check the <a href="https://marketdream.org/staff/bug_report.php">bug report panel</a>.<br />
        <br />Report number is <a href="https://marketdream.org/staff/bug_report.php#{$infohash}">{$infohash}</a>.
    </html>
EMAIL;
    $host = "smtpout.secureserver.net";
    $port = "3535";
    $password = "robotme";
 
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
 
    $mail = $smtp->send($to, $headers, $body);
}

function get_reports(){
	$sql = mysql_query("SELECT * FROM `bug_reports`");
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'report_id'	=> $row['report_id'],
            'report_email'   => $row['report_email'],
            'report_body' => $row['report_body']
        );
    }
    return $rows;
}

function log_change($id, $email, $symbol, $amount, $price, $buy = true, $aftermarket = true){
	$id = (int)$id;
	$email = html_escape($email);
	$symbol = strtoupper(html_escape($symbol));
	$amount = (int)$amount;
	$total = $price * $amount;
	$time = time();
	if(is_dir("/var/www/core/history/".$id) === false){
		mkdir("/var/www/core/history/".$id);
	}
	if(file_exists("/var/www/core/history/".$id."/history") === false){
		touch("/var/www/core/history/".$id."/history");
	}
	$json = array();

	$json = json_decode(file_get_contents("/var/www/core/history/{$id}/history"), true);
	if($buy === "short"){
		$json["Logs"][] = array("Timestamp" => $time, "Symbol" => $symbol, "Aftermarket" => $aftermarket, "Operation" => "short", "Quantity" => $amount, "Price" => $price, "Total" => $total);
	}elseif($buy === "cover"){
		$json["Logs"][] = array("Timestamp" => $time, "Symbol" => $symbol, "Aftermarket" => $aftermarket, "Operation" => "cover", "Quantity" => $amount, "Price" => $price, "Total" => $total);
	}elseif($buy == true){
		$json["Logs"][] = array("Timestamp" => $time, "Symbol" => $symbol, "Aftermarket" => $aftermarket, "Operation" => "buy", "Quantity" => $amount, "Price" => $price, "Total" => $total);
	}elseif($buy == false){
		$json["Logs"][] = array("Timestamp" => $time, "Symbol" => $symbol, "Aftermarket" => $aftermarket, "Operation" => "sell", "Quantity" => $amount, "Price" => $price, "Total" => $total);
	}

	file_put_contents("/var/www/core/history/{$id}/history", json_encode($json));
}

function freeze_account($id, $reason){
	$id = (int)$id;
	$reason = mysql_real_escape_string(html_escape($reason));
	
	mysql_query("INSERT INTO `freeze` (`user_id`, `reason`) VALUES ('{$id}', '{$reason}')");
}

function is_frozen($id){
	$id = (int)$id;
	$sql = mysql_query("SELECT COUNT(`user_id`), `reason` FROM `freeze` WHERE `user_id` = '{$id}'");
	if(mysql_result($sql, 0, 0) == 0){
		return false;
	}else{
		return mysql_result($sql, 0, 1);
	}
}

?>