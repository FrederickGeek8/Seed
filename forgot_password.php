<?php

include('core/init.inc.php');

if(isset($_SESSION['email'])){
  header('Location: index.php');
}

if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

$errors = array();

if(isset($_GET['fake'])){
  $fake = mysql_real_escape_string(html_escape($_GET['fake']));
  mysql_query("DELETE FROM `reset_codes` WHERE `reset_code` = '{$fake}'");
  header('Location: /');
  die();
}

if(isset($_GET['success'])){
  $info[] = "Successfully changed password.";
}

if(isset($_GET['id'])){
  if(valid_reset($_GET['id']) === false){
    $errors[] = 'Invalid reset id.';
  }
  if(isset($_POST['password'], $_POST['repeat_password'])){
    if(empty($_POST['password'])){
      $errors[] = 'The password cannot be empty.';
    }
    if($_POST['password'] !== $_POST['repeat_password']){
      $errors[] = 'Password verification failed';
    }

    if(empty($errors)){
      $reset_id = mysql_real_escape_string(html_escape($_GET['id']));
      $user_id = mysql_query("SELECT `user_id` FROM `reset_codes` WHERE `reset_code` = '{$reset_id}'");
      $user_id = mysql_result($user_id, 0);
      change_password($user_id, $_POST['password']);
      mysql_query("DELETE FROM `reset_codes` WHERE `reset_code` = '{$reset_id}' AND `user_id` = '{$user_id}'");
      header("Location: forgot_password?success");
    }
  }
}

if(isset($_POST['username'])){
	if(empty($_POST['username'])){
		$errors[] = 'You need to enter an username.';
	}else{
		if(user_exists($_POST['username']) === false){
			$errors[] = 'There is no user with that name.';
		}
	}

	if(empty($errors)){
    $tmail = get_user_contact($_POST['username']);
		reset_password(get_user_id($_POST['username']), $tmail);
    $info[] = "Please check your email for a reset link.";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['meta']; ?>">

    <!-- Le styles -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/assets/css/elusive-webfont.min.css">
    <style type="text/css">body {
    background:url("/assets/img/index-1.jpg") #eee;
    background-repeat:no-repeat;
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    padding-top:1.5%;
    padding-bottom: 1.5%;
}
.stease {
    margin-top:35px;
    color:white;
    text-align:center;
    font-size:10em;
    position:relative;
}
@media (max-width: 400px) {
    .stease {
        font-size:7.5em;
        margin-bottom:-10px;
    }
}
@media (max-width: 240px) {
    .stease {
        font-size:5em;
        margin-bottom:-20px;
    }
}
.alert {
    max-width:512px;
    margin:0 auto;
}
.form-signin {
  color:black;
    max-width:500px;
    padding:19px 29px 29px;
    margin:20px auto 20px;
    background:rgba(255,255,255,0.8);
    -webkit-box-shadow:0 1px 2px #aaa;
    -moz-box-shadow:0 1px 2px #aaa;
    box-shadow:0 1px 2px #aaa;
    border-radius:  1px;
}
.form-signin .form-signin-heading, .form-signin .checkbox {
    margin-bottom:10px
}
.form-signin input[type="email"], .form-signin input[type="password"], .form-signin input[type="text"] {
    font-size:16px;
    height:auto;
    margin-bottom:15px;
    padding:7px 9px
}
.form-signin>img {
    max-width:100%
}
.alert {
    position:relative;
    z-index:5;
    margin-bottom:5px;
}</style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43675987-1', 'auto');
  ga('send', 'pageview');

</script>
  </head>

  <body>
    <noscript><div style="padding-top:15%;width:100%;height:100%;top:0;left:0;right:0;bottom:0;z-index:1000;position:fixed;background:black;text-align:center;color:white;"><h1>Abort, Abort!</h1><h4>Your browser does not have Javascript enabled or does not support it.</h4><h6>Please check if there is a native application available for your system.</h6></div></noscript>

    <div class="container">
    <?php
  if (empty($info) === false){

        foreach ($info as $item){
          echo "<div class='alert alert-success'>{$item}</div>";
        }
    }
    if (empty($errors) === false){
        foreach ($errors as $error){
          echo "<div class='alert alert-danger'>{$error}</div>";
        }
    }

    ?>
    <div class="body">
    <h1 class='stease'>CAIC</h1>
      <?php if(isset($_GET['id']) && empty($errors)){
        ?><form class="form-signin" action="#" method="post">
        <h2>Change Password</h2>
        <input type="password" placeholder="Password" name="password" class="form-control">
        <input type="password" placeholder="Repeat Password" name="repeat_password" class="form-control">
        <button class="btn btn-large btn-danger" type="submit">Change Password</button>
      </form><?php
      }elseif(isset($_GET['id']) === false){
        ?><form class="form-signin" action="forgot_password" method="post">
        <h2>Forgot Password</h2>
        <input type="text" placeholder="Username" name="username" class="form-control">
        <button class="btn btn-large btn-danger" type="submit">Reset Password</button>
      </form><?php
      } ?>
      <div class="footer" style="text-align:center;margin-bottom:35px;">
        <p>Copyright &copy; <?php echo $siteinfo['title']." ".date("Y"); ?>. All rights reserved.</p>
      </div>
    </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
  </body>
</html>