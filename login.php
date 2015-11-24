<?php

//Deprecated page
header("HTTP/1.1 301 Moved Permanently");
header("Location: https://marketdream.org/");
die();

include('core/init.inc.php');

if (isset($_SESSION['email'])){
  header('Location: http://marketdream.org/');
}

if(isset($_SESSION['attempts']) === false){
  $_SESSION['attempts'] = 0;
}

if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

$errors = array();

if(isset($_GET['success'])){
  $errors[] = 'We have sent you an activation email. You need to activate your account to continue.';
}

if(isset($_GET['success2']) || isset($_GET['active'])){
  $errors[] = 'Your account has been successfully registered. Please login.';
}

if(isset($_GET['success3'])){
  $errors[] = 'Your account has been successfully upgraded.';
}

if (isset($_POST['email'], $_POST['password'])){
  
  if (valid_credentials($_POST['email'], $_POST['password']) === false){
    $errors[] = 'Email / Password incorrect.';
    $_SESSION['attempts']++;
  }

  if(isset($_POST['captcha']) && $_SESSION['attempts'] > 10){
    if(sha1('zgX"4C^S;^A|5U3Xa_>{"V:8t,So97TO^Gb|}vB^Vd;Yuob9b-5|$<:DHlDA[le'.strtolower($_POST['captcha'])) != $_SESSION['word']){
      $errors[] = 'The CAPTCHA is incorrect.';
    }
  }
  
  if (empty($errors) && is_active($_POST['email']) === false){
    $errors[] = 'This account is not yet activated. Please check your email for the activation email.';
  }
    
  if (empty($errors)){
    $_SESSION['email'] = html_escape($_POST['email']);
    $_SESSION['id'] = get_user_id($_SESSION['email']);

    header("Location: /login.php");
  }
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['description']; ?>">

    <!-- Le styles -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    /* GLOBAL STYLES
    -------------------------------------------------- */
    /* Padding below the footer and lighter body text */
	/* Because mobile devices are stupid */ html{margin:0;height:100%}
	.footer a{background:white;padding:2px}
	.footer a:hover{background:lightgrey}
    body {
      padding-bottom: 40px;
      color: #5a5a5a;
      background: url("/assets/img/wallstreet-1.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center center;
      background-attachment: fixed;
      <?php if($android){ ?>
      background:url("http://marketdream.org/assets/img/use_your_illusion.png");
      background-repeat: repeat;
      background-size: scroll;	
      <?php } ?>
    }
    
    .body{
    	color:white;
	    margin-top:7%;	
	    padding: 15px;
	    border-radius: 3px;
	    width: 65%;
    }
    
    .jumbotron{
	    text-align: center;	
	    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }



    /* CUSTOMIZE THE NAVBAR
    -------------------------------------------------- */

    /* Special class on .container surrounding .navbar, used for positioning it into place. */
    .navbar-wrapper {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 10;
      margin-top: 20px;
      margin-bottom: -90px; /* Negative margin to pull up carousel. 90px is roughly margins and height of navbar. */
    }
    .navbar-wrapper .navbar {

    }

    /* Remove border and change up box shadow for more contrast */
    .navbar .navbar-inner {
      border: 0;
      -webkit-box-shadow: 0 2px 10px rgba(0,0,0,.25);
         -moz-box-shadow: 0 2px 10px rgba(0,0,0,.25);
              box-shadow: 0 2px 10px rgba(0,0,0,.25);
    }

    /* Downsize the brand/project name a bit */
    .navbar .brand {
      padding: 14px 20px 16px; /* Increase vertical padding to match navbar links */
      font-size: 16px;
      font-weight: bold;
      text-shadow: 0 -1px 0 rgba(0,0,0,.5);
    }

    /* Navbar links: increase padding for taller navbar */
    .navbar .nav > li > a {
      padding: 15px 20px;
    }

    /* Offset the responsive button for proper vertical alignment */
    .navbar .btn-navbar {
      margin-top: 10px;
    }



    /* MARKETING CONTENT
    -------------------------------------------------- */

    /* Center align the text within the three columns below the carousel */
    .marketing{
	    margin-left: 17.5%;
    }
    .marketing h2 {
      font-weight: normal;
    }
    .marketing .span4 p {
      margin-left: 10px;
      margin-right: 10px;
    }
    
    .marketing .span4 p > a{
	    background:white;
	    padding: 2px;
    }
    
    .marketing .span4 p > a:hover{
	    background:lightgrey;
    }
    
    @media (max-width: 979px) {

      .container.navbar-wrapper {
        margin-bottom: 0;
        width: auto;
      }
      .navbar-inner {
        border-radius: 0;
        margin: -20px 0;
      }
      .lead{
	      font-size: 1em;
	      
      }
      .marketing .span4{
	      max-width: 75%;
      }
    }


    @media (max-width: 767px) {
      .navbar-inner {
        margin: -20px;
      }
      
      .lead{
	      display: none;
      }
      .marketing .span4 p{
	      display: none;
      }
      .marketing .span4{
	      max-width: 200%;
      }
      .jumbotron{
	      margin-top:25px;
      }
      .marketing .span4 h4{
	      background: rgba(255, 225, 225, 0.2);
	      padding:5px;
	      margin-left:-25%;
      }
    }
.form-signin{color:black;max-width:300px;padding:19px 29px 29px;margin:0 auto 20px;background-color:#fff;border:1px solid #e5e5e5;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.05);-moz-box-shadow:0 1px 2px rgba(0,0,0,.05);box-shadow:0 1px 2px rgba(0,0,0,.05)}.form-signin .form-signin-heading,.form-signin .checkbox{margin-bottom:10px}.form-signin input[type="text"],.form-signin input[type="password"]{font-size:16px;height:auto;margin-bottom:15px;padding:7px 9px}</style>
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
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
	<div class="navbar-wrapper">
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->
      <div class="container">

        <div class="navbar navbar-inverse">
          <div class="navbar-inner">
            <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#"><?php echo $siteinfo['title']; ?></a>
            <!-- Responsive Navbar Part 2: Place all navbar contents you want collapsed withing .navbar-collapse.collapse. -->
            <div class="nav-collapse collapse">
              <ul class="nav">
                <li><a href="/home">Home</a></li>
                <li><a href="/toolbox"><?=$viewd?>Toolbox</a></li>
                <li><a href="/market">Market</a></li>
                <li><a href="/leaderboard">Leaderboard</a></li>
                <li><a href="/portfolio">Portfolio</a></li>
              </ul>
              <ul class="nav pull-right"><li class="active"><a href="/login"><i class='icon-key'></i>Login</a></li></ul>
            </div><!--/.nav-collapse -->
          </div><!-- /.navbar-inner -->
        </div><!-- /.navbar -->

      </div> <!-- /.container -->
    </div><!-- /.navbar-wrapper -->

    <div class="container body">

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

      <form class="form-signin" action='login.php' method='post'>
        Need an account? Register <a href="register.php">here</a>.
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="Email address" name='email' value='<?php if(isset($_POST['email'])){ echo html_escape($_POST['email']); } ?>' />
        <input type="password" class="input-block-level" placeholder="Password" name='password' />
        <p><small><a href="forgot_password.php">Forgot your password?</a></small></p>
        <?php if($_SESSION['attempts'] > 10){ ?>
        <p><small>Please enter the text below.</small></p>
        <img src="captcha/image.php">
        <br /><br />
        <input type="text" class="input-block-level" placeholder="Answer" name="captcha">
        <?php } ?>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
      </form>

      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
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
