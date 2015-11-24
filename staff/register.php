<?php

include('../core/init.inc.php');

if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

function valid_invite($id){
    $id = mysql_real_escape_string($id);
    $total = mysql_query("SELECT COUNT(`invite_code`) FROM `invites` WHERE `invite_code` = '{$id}'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function register_staff($email, $alias, $pass, $admin){
	$email = mysql_real_escape_string(html_escape($email));
	$alias = mysql_real_escape_string(html_escape($alias));
	$pass = crypt(urlencode($pass));
  mysql_query("INSERT INTO `users` (`user_email`) VALUES ('<a></a>')");
  $user_id = mysql_insert_id();
  mysql_query("DELETE FROM `users` WHERE `user_id` = '{$user_id}'");
  mysql_query("INSERT INTO `users_strict` (`user_id`, `user_email`, `user_password`) VALUES ('{$user_id}', '{$email}', '{$pass}')");
  if($admin == '1'){
    mysql_query("INSERT INTO `admins` (`user_id`, `alias`) VALUES ('{$user_id}', '{$alias}')");
  }else{
    mysql_query("INSERT INTO `writers` (`user_id`, `alias`) VALUES ('{$user_id}', '{$alias}')");
  }
}

$errors = array();
if(isset($_GET['i'])){
	if(valid_invite($_GET['i']) === false){
		die('This is not a valid invite code.');
	}
  $code = mysql_real_escape_string($_GET['i']);
  $code = mysql_query("SELECT `invite_email` AS `email`, `admin` FROM `invites` WHERE `invite_code` = '{$code}'");
  $code = mysql_fetch_assoc($code);
	if(isset($_POST['password'], $_POST['email'], $_POST['alias'])){
		if(empty($_POST['password']) || empty($_POST['email']) || empty($_POST['alias']) || empty($_POST['repeat_password'])){
			$errors[] = 'All fields must be filled out.';
		}
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
    		$errors[] = 'The email address you entered does not appear to be valid.';
  		}
  		if($_POST['repeat_password'] !== $_POST['password']){
  			$errors[] = 'Password verification failed.';
  		}
  		if (email_exists($_POST['email'])){
    		$errors[] = 'The email you entered has already been registered with an account.';
  		}
  		if(empty($errors)){
  			register_staff($_POST['email'], $_POST['alias'], $_POST['password'], $code['admin']);
	  		$invite = mysql_real_escape_string($_GET['i']);
	  		mysql_query("DELETE FROM `invites` WHERE `invite_code` = '{$invite}'");
	  		header('Location: /login.php?success2');
	  		die();
  		}
	}
}else{
	die('Invite code not specified.');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IFBF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>
    <noscript><div style="padding-top:15%;width:100%;height:100%;top:0;left:0;right:0;bottom:0;z-index:1000;position:fixed;background:black;text-align:center;color:white;"><h1>Abort, Abort!</h1><h4>Your browser does not have Javascript enabled or does not support it.</h4><h6>Please check if there is a native application available for your system.</h6></div></noscript>

    <div class="container"><div class="alert">
  <strong>Warning!</strong> Signing up for an Admin or Writer account removes the ability to participate in the competiton!
</div>
    	<p>
        <?php

        if (empty($errors) === false){
          ?>
          <ul>
            <?php

            foreach ($errors as $error){
              echo "<li>{$error}</li>";
            }

            ?>
          </ul>
          <?php
        }

        ?>
      </p>
      <form class="form-signin" method='post' action='#'>
        <h2 class="form-signin-heading"><?php if($code['admin'] == '1'){ echo 'Admin'; }else{ echo 'Writer'; } ?> Signup</h2>
        <input type="text" class="input-block-level" placeholder="Email address" name='email' value='<?php if(isset($_POST['email'])){ echo html_escape($_POST['email']); }else{ echo $code['email']; } ?>' required>
        <input type="text" class="input-block-level" placeholder="Alias" name='alias' value='<?php if(isset($_POST['alias'])){ echo html_escape($_POST['alias']); } ?>' required>
        <input type="password" class="input-block-level" placeholder="Password" name='password' required>
        <input type="password" class="input-block-level" placeholder="Repeat Password" name='repeat_password' required>
        <button class="btn btn-large btn-primary" type="submit">Sign up</button>
      </form>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
  </body>
</html>
