<?php

if(isset($_SESSION['email']) === false){
    header('Location: /login.php');
    die();
}

$id = get_user_id($_SESSION['email']);
if(is_admin($id)){
    $permission_level = 'Admin';
    $admin = true;
    $nav = "<li><a href=\"index.php\"><i class='icon-home'></i>Home</a></li>
          <li><a href=\"add_post.php\"><i class='icon-file-edit-alt'></i>Add Post</a></li>
          <li><a href=\"posts.php\"><i class='icon-file-alt'></i>Posts</a></li>
          <li><a href=\"splits.php\"><i class='icon-fullscreen'></i>Splits</a></li>
          <li><a href=\"users.php\"><i class='icon-group-alt'></i>Users</a></li>
          <li><a href=\"settings.php\"><i class='icon-cog-alt'></i>Settings</a></li>";
}else{
    if(is_writer($id)){
        $admin = false;
        $permission_level = 'Writer';
        $nav = "<li><a href=\"index.php\"><i class='icon-home'></i>Home</a></li>
          <li><a href=\"add_post.php\"><i class='icon-file-edit-alt'></i>Add Post</a></li>
          <li><a href=\"posts.php\"><i class='icon-file-alt'></i>Posts</a></li>
          <li><a href=\"settings.php\"><i class='icon-cog-alt'></i>Settings</a></li>";
    }else{
        die('You are not staff. You have been logged');
    }
}

function bbcode($text) {
    $text = nl2br(html_escape($text, ENT_QUOTES, "UTF-8"));
    $text = str_replace("\n", "", $text);
    $search = array(
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[s\](.*?)\[\/s\]/is',
            '/\[sub\](.*?)\[\/sub\]/is',
            '/\[sup\](.*?)\[\/sup\]/is',
            '/\[img\](.*?)\[\/img\]/is',
            '/\[url\](.*?)\[\/url\]/is',
            '/\[url\=(.*?)\](.*?)\[\/url\]/is',
            '/\[size\=(.*?)\](.*?)\[\/size\]/is',
            '/\[color\=(.*?)\](.*?)\[\/color\]/is',
            '/\[center\](.*?)\[\/center\]/is',
            '/\[right\](.*?)\[\/right\]/is',
            '/\[left\](.*?)\[\/left\]/is',
            '/\[justify\](.*?)\[\/justify\]/is',
            '/\[youtube\](.*?)\[\/youtube\]/is',
            '/\[ul\](.*?)\[\/ul\]/is',
            '/\[ol\](.*?)\[\/ol\]/is',
            '/\[li\](.*?)\[\/li\]/is',
            '/\[code\](.*?)\[\/code\]/is',
            '/\[quote\](.*?)\[\/quote\]/is',
            '/\[hr\]/',
            '/\[email\=(.*?)\](.*?)\[\/email\]/is',
            '/\[rtl\](.*?)\[\/rtl\]/is',
            '/\[ltr\](.*?)\[\/ltr\]/is',
            '/\[table\](.*?)\[\/table\]/is',
            '/\[tr\](.*?)\[\/tr\]/is',
            '/\[td\](.*?)\[\/td\]/is',
            '/\[thead\](.*?)\[\/thead\]/is',
            '/\[tbody\](.*?)\[\/tbody\]/is',
            '/\[th\](.*?)\[\/th\]/is',
            '/\[caption\](.*?)\[\/caption\]/is',
            '/\[vimeo\](.*?)\[\/vimeo\]/is',
            );

    $replace = array(
            "<strong>$1</strong>",
            "<em>$1</em>",
            "<u>$1</u>",
            "<del>$1</del>",
            "<sub>$1</sub>",
            "<sup>$1</sup>",
            "<img src=\"$1\" />",
            "<a href=\"$1\">$1</a>",
            "<a href=\"$1\">$2</a>",
            "<font size=\"$1\">$2</font>",
            "<a style='color:$1;'>$2</a>",
            "<center>$1</center>",
            "<div style=\"text-align:right;\">$1</div>",
            "<div style=\"text-align:left;\">$1</div>",
            "<div style=\"text-align:justify;\">$1</div>",
            "<iframe id=\"ytplayer\" type=\"text/html\" width=\"640\" height=\"360\"
src=\"https://www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen>",
            "<ul>$1</ul>",
            "<ol>$1</ol>",
            "<li>$1</li>",
            "<code>$1</code>",
            "<blockquote>$1</blockquote>",
            "<hr />",
            "<a href='mailto:$1'>$2</a>",
            "<div style='direction: rtl'>$1</div>",
            "<div style='direction: ltr'>$1</div>",
            "<table>$1</table>",
            "<tr>$1</tr>",
            "<td>$1</td>",
            "<thead>$1</thead>",
            "<tbody>$1</tbody>",
            "<th>$1</th>",
            "<caption>$1</caption>",
            "<iframe src=\"http://player.vimeo.com/video/$1\" width=\"500\" height=\"281\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>"
            );
    $bb = preg_replace ($search, $replace, $text);

    return $bb;
}

function html_to_bbcode($html){
    $html = str_replace('<br />', '', $html);
    $replace = array(
            '[b]$1[/b]',
            '[i]$1[/i]',
            '[u]$1[/u]',
            '[s]$1[/s]',
            '[sub]$1[/sub]',
            '[sup]$1[/sup]',
            '[img]$1[/img]',
            '[url]$1[/url]',
            '[url=$1]$2[/url]',
            '[size=$1]$2[/size]',
            '[color=$1]$2[/color]',
            '[center]$1[/center]',
            '[right]$1[/right]',
            '[left]$1[/left]',
            '[justify]$1[/justify]',
            '[youtube]$1[/youtube]',
            '[font=$1]$2[/font]',
            '[ul]$1[/ul]',
            '[ol]$1[/ol]',
            '[li]$1[/li]',
            '[code]$1[/code]',
            '[quote]$1[/quote]',
            '[hr]',
            '[email=$1]$2[/email]',
            '[rtl]$1[/rtl]',
            '[ltr]$1[/ltr]',
            '[table]$1[/table]',
            '[tr]$1[/tr]',
            '[td]$1[/td]',
            '[thead]$1[/thead]',
            '[tbody]$1[/tbody]',
            '[th]$1[/th]',
            '[caption]$1[/caption]',
            '[vimeo]$1[/vimeo]'
            );

    $search = array(
            '/<strong>(.*?)<\/strong>/is',
            "/<em>(.*?)<\/em>/is",
            "/<u>(.*?)<\/u>/is",
            "/<del>(.*?)<\/del>/is",
            "/<sub>(.*?)<\/sub>/is",
            "/<sup>(.*?)<\/sup>/is",
            "/<img src=\"(.*?)\" \/>/is",
            "/<a href=\"(.*?)\">(.*?)<\/a>/is",
            "/<a href=\"(.*?)\">(.*?)<\/a>/is",
            "/<font size=\"(.*?)\">(.*?)<\/font>/is",
            "/<a style='color:(.*?);'>(.*?)<\/a>/is",
            "/<center>(.*?)<\/center>/is",
            "/<div style=\"text-align:right;\">(.*?)<\/div>/is",
            "/<div style=\"text-align:left;\">(.*?)<\/div>/is",
            "/<div style=\"text-align:justify;\">(.*?)<\/div>/is",
            "/<iframe id=\"ytplayer\" type=\"text\/html\" width=\"640\" height=\"360\" src=\"https:\/\/www.youtube.com\/embed\/(.*?)\" frameborder=\"0\" allowfullscreen>/is",
            "/<a style='font-family:(.*?);'>(.*?)<\/a>/is",
            "/<ul>(.*?)<\/ul>/is",
            "/<ol>(.*?)<\/ol>/is",
            "/<li>(.*?)<\/li>/is",
            "/<code>(.*?)<\/code>/is",
            "/<blockquote>(.*?)<\/blockquote>/is",
            "/<hr \/>/is",
            "/<a href='mailto:(.*?)'>(.*?)<\/a>/is",
            "/<div style='direction: rtl'>(.*?)<\/div>/is",
            "/<div style='direction: ltr'>(.*?)<\/div>/is",
            "/<table>(.*?)<\/table>/is",
            "/<tr>(.*?)<\/tr>/is",
            "/<td>(.*?)<\/td>/is",
            "/<thead>(.*?)<\/thead>/is",
            "/<tbody>(.*?)<\/tbody>/is",
            "/<th>(.*?)<\/th>/is",
            "/<caption>(.*?)<\/caption>/is",
            "/<iframe src=\"http:\/\/player.vimeo.com\/video\/(.*?)\" width=\"500\" height=\"281\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen><\/iframe>/is"
            );
    $bb = preg_replace ($search, $replace, $html);

    return $bb;
}

function promote_user($id){
    global $siteinfo;
    $id = (int)$id;
    if(is_admin($id)){
        return false;
    }elseif(is_writer($id)){
        $sql = mysql_query("SELECT `alias` FROM `writers` WHERE `user_id` = '{$id}'");
        $alias = mysql_result($sql, 0);
        mysql_query("DELETE FROM `writers` WHERE `user_id` = '{$id}'");
        mysql_query("INSERT INTO `admins` (`user_id`, `alias`) VALUES ('{$id}', '{$alias}')");
        return 'writer';
    }else{
        $email = mysql_query("SELECT `user_email` FROM `users` WHERE `user_id` = '{$id}'");
        $email = mysql_result($email, 0);
        $charset = array_flip(array_merge(range('a' ,'z'), range('A', 'Z'), range('0', '9')));
        $upgrade_code = sha1(implode('', array_rand($charset, 15)));

        $to = $email;
        $username = "noreply@marketdream.org";
        $from = "{$siteinfo['title']} <{$username}>";
        $subject = "Upgrade! - {$siteinfo['title']}";
        $body = <<<EMAIL
        <html>

        Hello,<br />
        <br />
        You have been invited to become a Writer at {$siteinfo['title']}! Please follow the link below to upgrade your account.<br />
        <a href='http://marketdream.org/staff/upgrade.php?i={$upgrade_code}'>http://marketdream.org/staff/upgrade.php?i={$upgrade_code}</a><br />
        <br />
        If you believe this invitation is a mistake, please contact us at <a href='mailto:admin@marketdream.org'>admin@marketdream.org</a>

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
        mysql_query("INSERT INTO `invites` (`invite_code`, `invite_email`) VALUES ('{$upgrade_code}', '{$email}')");
        return 'newwriter';
    }
}

function send_invite($email, $type){
    global $siteinfo;
    $email = mysql_real_escape_string(html_escape($email));
    $charset = array_flip(array_merge(range('a' ,'z'), range('A', 'Z'), range('0', '9')));
    $invite_code = sha1(implode('', array_rand($charset, 15)));
    switch ($type) {
        case 'school':
           $body = <<<EMAIL
        <html>

        On behalf of The Cushing Academy Investment Club, we wish to invite you to participate in our first annual investment competition Beginning September 30, 2013.<br />
        <br />
        {$siteinfo['description']} <br />
        <br />Registration Dead Line September 16, 2013<br />
        <br />
        Please register an account at: <a href='http://marketdream.org/index-register.php'>http://marketdream.org/index-register.php</a><br />
        <br />
        If you believe this invitation is a mistake, please ignore this email.

        </html>
EMAIL;
            break;

        case 'writer':
            $body = <<<EMAIL
        <html>

        Hello,<br />
        <br />
        You have been invited to become a Writer at {$siteinfo['title']}! Please follow the link below to create your account.<br />
        <a href='http://marketdream.org/staff/register.php?i={$invite_code}'>http://marketdream.org/staff/register.php?i={$invite_code}</a><br />
        <br />
        If you believe this invitation is a mistake, please contact us at <a href='mailto:admin@marketdream.org'>admin@marketdream.org</a>

        </html>
EMAIL;
            mysql_query("INSERT INTO `invites` (`invite_code`) VALUES ('{$invite_code}')");
            break;

        case 'admin':
            $body = <<<EMAIL
        <html>

        Hello,<br />
        <br />
        You have been invited to become an Administrator at {$siteinfo['title']}! Please follow the link below to create your account.<br />
        <a href='http://marketdream.org/staff/register.php?i={$invite_code}'>http://marketdream.org/staff/register.php?i={$invite_code}</a><br />
        <br />
        If you believe this invitation is a mistake, please contact us at <a href='mailto:admin@marketdream.org'>admin@marketdream.org</a>

        </html>
EMAIL;
            mysql_query("INSERT INTO `invites` (`invite_code`, `admin`, `invite_email`) VALUES ('{$invite_code}', '1', '{$email}')");
            break;
        default:
            return false;
            break;
    }

    $to = $email;
    $username = "noreply@marketdream.org";
    $from = "{$siteinfo['title']} <{$username}>";
    $subject = "Invitation from {$siteinfo['title']}";
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
}

function resolve_bug($id){
	global $siteinfo;
	$id = (int)$id;
	$email = mysql_query("SELECT COUNT(`report_email`)AS `count`, `report_email` AS `email` FROM `bug_reports` WHERE `report_id` = '{$id}'");
	$email = mysql_fetch_assoc($email);
	if($email['count'] == '0'){
		return false;
	}
	$email = $email['email'];
	mysql_query("DELETE FROM `bug_reports` WHERE `report_id` = '{$id}' AND `report_email` = '{$email}'");
	$to = $email;
    $username = "bugreport@marketdream.org";
    $from = "Bugbot <{$username}>";
    $subject = "Bug Report Resolved";
    $body = <<<EMAIL
    <html>
        The bug report you submitted to {$siteinfo['title']} has been resolved.<br />
        <br />Thanks,<br />
        {$siteinfo['title']} Team.
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

    $mail = $smtp->send($email, $headers, $body);
}

function demote_user($id){
    $id = (int)$id;
    if(is_admin($id)){
        $sql = mysql_query("SELECT `alias` FROM `admins` WHERE `user_id` = '{$id}'");
        $alias = mysql_result($sql, 0);
        mysql_query("DELETE FROM `admins` WHERE `user_id` = '{$id}'");
        mysql_query("INSERT INTO `writers` (`user_id`, `alias`) VALUES ('{$id}', '{$alias}')");
    }elseif(is_writer($id)){
        return false;
    }else{
        return false;
    }
}

function add_split($ratio, $symbol, $exdate, $paydate){
	$symbol = mysql_real_escape_string(html_escape($symbol));
	mysql_query("INSERT INTO `splits` (`split_symbol`, `split_ratio`, `split_exdate`, `split_paydate`) VALUES ('{$symbol}', '{$ratio}', '{$exdate}', '{$paydate}')");
}

?>
