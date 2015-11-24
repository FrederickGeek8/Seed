<?php

include('../core/init.inc.php');
$_SESSION = array();
session_destroy();

if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

if(isset($_GET['i'])){
	$upgradecode = mysql_real_escape_string($_GET['i']);
	function valid_invite_email($id){
		$id = mysql_real_escape_string($id);
		$total = mysql_query("SELECT COUNT(`invite_code`), `invite_code`, `invite_email` FROM `invites` WHERE `invite_code` = '{$id}'");
		$total = mysql_fetch_assoc($total);
		if($total['COUNT(`invite_code`)'] == '0'){
			return false;
		}else{
			if($total['invite_email'] == ''){
				return false;
			}else{
				return true;
			}
		}
	}
	if(valid_invite_email($_GET['i']) === false){
		die('Upgrade code invalid');
	}
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM `invites` WHERE `invite_code` = '{$upgradecode}'");
		die('Your writer signup pass has been deleted.');
	}
	if(isset($_POST['password'], $_POST['repeat_password'], $_POST['alias'])){
		$errors = array();
		$username = mysql_query("SELECT `invite_email` FROM `invites` WHERE `invite_code` = '{$upgradecode}'");
		$username = mysql_result($username, 0);
		if($_POST['password'] !== $_POST['repeat_password']){
			$errors[] = 'The passwords you enter do not match.';
		}
		if(valid_credentials($username, $_POST['password']) === false){
			$errors[] = 'Password incorrect.';
		}
		if(empty($errors)){
			$stats = mysql_query("SELECT `user_id`, `user_password` FROM `users` WHERE `user_email` = '{$username}'");
			$stats = mysql_fetch_assoc($stats);
			mysql_query("INSERT INTO `users_strict` (`user_id`, `user_email`, `user_password`) VALUES ('{$stats['user_id']}', '{$username}', '{$stats['user_password']}')");
			mysql_query("DELETE FROM `users` WHERE `user_email` = '{$username}'");
			mysql_query("DELETE FROM `invites` WHERE `invite_email` = '{$username}'");
			$alias = mysql_real_escape_string(html_escape($_POST['alias']));
			mysql_query("INSERT INTO `writers` (`user_id`, `alias`) VALUES ('{$stats['user_id']}', '{$alias}')");
			mysql_query("DELETE FROM `user_stocks` WHERE `user_id` = '{$stats['user_id']}'");
			mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$stats['user_id']}'");
			header("Location: /login.php?success3");
			die();
		}
	}
}else{
	die('Upgrade code not specified');
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

    <div class="container">
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
      <div class="form-signin">
        <p>Are you sure that you want to upgrade your account? <b>All</b> normal-operation account infomation will be deleted. You will be <b>removed</b> from the competition and will not be able to participate.</p>
        <p style="text-align:center;"><a href="#" data-toggle="modal" role='button' data-target="#yes" class='btn btn-warning'>Yes</a>&emsp;<a data-toggle="modal" role='button' data-target="#no" class='btn'>No</a></p>
        <div id="yes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Verification</h3>
		  </div>
		  <div class="modal-body">
		    <p>Please enter your password twice to verify your identity.</p>
		    <p><form action='#' class='well' style='text-align:center;' id='verify' method='post'><p><input type='password' placeholder='Password' name='password' required></p><p><input type='password' placeholder='Repeat Password' name='repeat_password' required></p>
		  	<p>Additionally, pick an alias.<p><input type='text' placeholder='Alias' name='alias' required></p></p></form></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		    <button class="btn btn-warning" type='submit' form='verify'>Upgrade</button>
		  </div>
		</div>
		<div id="no" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
		  <div class="modal-body" style='text-align:center;'>
			<p><a href="register.php?i=<?php echo html_escape($_GET['i']); ?>" class='btn btn-success'>No, but create me a new account</a></p><h4>OR</h4><p><a href="upgrade.php?i=<?php echo html_escape($_GET['i']); ?>&delete" class='btn'>No, I do not want to become a Writer</a></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>