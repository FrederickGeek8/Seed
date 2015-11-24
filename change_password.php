<?php

include('core/init.inc.php');

if(isset($_SESSION['email']) === false){
  header('Location: index.php');
}

if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

$errors = array();
$info = array();

if(isset($_GET['success'])){
  $info[] = 'Your password has been changed.';
}

if(isset($_POST['old_password'], $_POST['password'], $_POST['repeat_password'])){
	if(empty($_POST['password'])){
	  $errors[] = 'The password cannot be empty.';
	}

	if($_POST['password'] !== $_POST['repeat_password']){
	  $errors[] = 'Password verification failed';
	}

	if(valid_credentials($_SESSION['email'], $_POST['old_password']) === false){
		$errors[] = 'The old password you entered is incorrect.';
	}

	if(empty($errors)){
	  change_password($_SESSION['id'], $_POST['password']);
	  header("Location: change_password.php?success");
    die();
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
    <meta name="description" content="<?php echo $siteinfo['description']; ?>">

    <style>

    body {
      background: url("/assets/img/wallstreet-1.jpg");
      <?php if($android){ ?>
      background:url("http://marketdream.org/assets/img/use_your_illusion.png");
      background-repeat: repeat;
      background-size: scroll;  
      <?php } ?>
    }
    .form-signin{color:#000;max-width:500px;padding:19px 29px 29px;margin:20px auto;background:rgba(255,255,255,.8);-webkit-box-shadow:0 1px 2px #aaa;-moz-box-shadow:0 1px 2px #aaa;box-shadow:0 1px 2px #aaa;border-radius:1px}.form-signin .checkbox,.form-signin .form-signin-heading{margin-bottom:10px}.form-signin input[type=email],.form-signin input[type=password],.form-signin input[type=text]{font-size:16px;height:auto;margin-bottom:15px;padding:7px 9px}.form-signin>img{max-width:100%}
    </style>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

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
    <div class="container">
      <noscript><div style="padding-top:15%;width:100%;height:100%;top:0;left:0;right:0;bottom:0;z-index:1000;position:fixed;background:black;text-align:center;color:white;"><h1>Abort, Abort!</h1><h4>Your browser does not have Javascript enabled or does not support it.</h4><h6>Please check if there is a native application available for your system.</h6></div></noscript>
      <!-- Static navbar -->
      <div class="navbar" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><?php echo $siteinfo['title']; ?></a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="/home">Home</a></li>
              <li><a href="/toolbox"><?=$viewd?>Toolbox</a></li>
              <li><a href="/market">Market</a></li>
              <li><a href="/leaderboard">Leaderboard</a></li>
              <li><a href="/portfolio">Portfolio</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <?=$rightnav?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      <div class="body">
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

      <?php if(empty($errors)){
        ?><form class="form-signin" action="#" method="post">
        <h2>Change Password</h2>
        <input type="password" class="form-control" placeholder="Old Password" name="old_password" required>
        <input type="password" class="form-control" placeholder="New Password" name="password" required>
        <input type="password" class="form-control" placeholder="Repeat New Password" name="repeat_password" required>
        <button class="btn btn-large btn-danger" type="submit">Change Password</button>
      </form><?php
      }else{

      } ?>
      

      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
      </div>
    </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

  </body>
</html>