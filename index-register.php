<?php

include('core/init.inc.php');

if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

$errors = array();
$info = array();

if(isset($_GET['success'])){
  $info[] = 'We have sent you an activation email. You need to activate your account to continue.';
}

if(isset($_GET['success2']) || isset($_GET['active'])){
  $info[] = 'Your account has been successfully activated.';
}

if (isset($_POST['school'], $_POST['password'], $_POST['repeat_password'], $_POST['email'], $_POST['captcha'])){

  if(sha1('zgX"4C^S;^A|5U3Xa_>{"V:8t,So97TO^Gb|}vB^Vd;Yuob9b-5|$<:DHlDA[le'.strtolower($_POST['captcha'])) != $_SESSION['word']){
    $errors[] = 'The CAPTCHA is incorrect.';
  }

  if (empty($_POST['school'])){
    $errors[] = 'The school cannot be empty.';
  }
  
  if($_POST['terms'] != 'accepted'){
	  $errors[] = 'You must agree to the Terms & Conditions and our Privacy Policy.';
  }
  
  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
    $errors[] = 'The email address you entered does not appear to be valid.';
  }

  if (empty($_POST['password']) || empty($_POST['repeat_password'])){
    $errors[] = 'The password cannot be empty.';
  }

  if ($_POST['password'] !== $_POST['repeat_password']){
    $errors[] = 'Password verification failed.';
  }
  
   if (school_exists($_POST['school'])){
     $errors[] = 'The school name you entered is already taken.';
  }
  
  if (email_exists($_POST['email'])){
    $errors[] = 'The email you entered has already been registered with an account.';
  }

  if (empty($errors)){
    add_manager(0, $_POST['school'], $_POST['email'], $_POST['password']);
    header('Location: /?success');
    die();
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IFBF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['meta']; ?>">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/css/elusive-webfont.min.css">
    <style type="text/css">body {
        background:url("assets/img/index-1.jpg") #eee;
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
    <h1 class='stease' title="Investing For Better Futures">IFBF</h1>
    <h4 style="text-align:center;" id="subtitle">Hosting the Cushing Academy Investment Competition</h4>
      <div class="form-signin">
        <div style="text-align:center"><div class="demo in"><a class="btn btn-danger btn-lng" style="margin-top:32px;margin-bottom:16px;width:232px;" href="#CAIC" data-toggle="collapse" data-target=".demo" onclick='$(".stease").fadeOut(function() {$(this).text("CAIC").fadeIn();});$("#subtitle").animate({"height": 0,"opacity": 0}, function() {$("#subtitle").remove();});document.title = "CAIC";'>Register for CAIC</a><br><a class="btn btn-success btn-lng" href="/team/login" style="width:232px;">Login</a><hr/><h3>Who We Are</h3><p class="paragraph">Established as a robust and dynamic competition, Investing for Better Futures (IFBF), looks to promote financial literacy and cultivate students to be more astute and alert to economic activity. The intentions of this competition are be three fold. First, we sincerely believe in focusing on a full range of economic successes through education and practice, with an ethical approach of the highest integrity. Second, our efforts involve improving a high comprehension of an entrepreneurial spirit that has ushered capitalism since its foundation. Finally, we focus profoundly upon the importance of social conscience and the tradition of morality. We look to the ideologies of Adam Smith in "The Wealth of Nations" to set the precedent for maximizing economic profit yet eliminating economic greed. Our secondary focuses entail the effects of the "Invisible Hand," the benefits of competitive markets, and how people acting toward their own self-interest is beneficial to everyone.</p><p class="paragraph">Currently we are hosting a competition for Cushing Academy (CAIC), tailoring to global high school students. If you are interested in hosting or creating your own competition, please feel free to <script type="text/javascript">document.write("<a rel=\"nofollow\" href=\"mailto"); document.write(":" + "admin" + "@"); document.write("marketdream.org" + "?subject=" + "New Competition" + "\">" + "contact us" + "<\/a>");</script>.</p><p style="text-align:left"><small>Note: Because we are currently in the process of upgrading our system, the live demo and presentation are temporarily unavailable and will become available again in the coming month.</small></p></div></div>

        <form class="collapse demo" action='/' method='post'>
          <h1>Register A School<sup><small>*</small></sup></h1>
          <br />
          <input type="text" class='form-control' placeholder="School Name" name='school' value='<?php if(isset($_POST['school'])){ echo html_escape($_POST['school']); }elseif(isset($_GET['s'])){ echo html_escape(base64_decode($_GET['s'], true)); } ?>' required>
          <input type="email" class="form-control" placeholder="Email address" name='email' value='<?php if(isset($_POST['email'])){ echo html_escape($_POST['email']); } ?>' required>
          <input type="password" class="form-control" placeholder="Password" name='password' required>
          <input type="password" class="form-control" placeholder="Repeat Password" name='repeat_password' required>
          <p><small>Please enter the text below.</small></p>
          <img src="captcha/image" alt="captcha" style="max-width:100%;">
          <br /><br />
          <input type="text" class="form-control" placeholder="Answer" name="captcha" required>
          <p class="checkbox"><label><input type="checkbox" name="terms" value="accepted" required> I agree to the <?=$siteinfo['title']?> <a href="#" onclick="window.open('terms','name','height=512,width=1024')">Terms of Service</a> and <a href="#" onclick="window.open('privacy','name','height=512,width=1024')">Privacy Policy</a></label></p>
          <p><small>* Please note that you only need to register ONE account per school. This should not be a user account but a team manager account, where you may create user accounts.</small></p>
          <button class="btn btn-large btn-primary" type="submit">Sign up</button>&nbsp;&nbsp;<a class="btn btn-large btn-danger" href="/">Cancel</a>
        </form>
      </div>
      <div class="footer" style="text-align:center;margin-bottom:35px;">
        <p>Copyright &copy; <?php echo "Investing For Better Futures ".date("Y"); ?>. All rights reserved.</p>
      </div>
    </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var anchor = window.location.hash.replace("#", "");
            if (anchor == "CAIC") { $(".demo").collapse('toggle'); $(".stease").fadeOut(function() {$(this).text("CAIC").fadeIn();}); $("#subtitle").animate({'height': 0,'opacity': 0}, function() {$("#subtitle").remove();}); document.title = "CAIC"; }; 
        });
    </script> 
    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-43675987-1', 'auto');
	  ga('send', 'pageview');
	
	</script>
  </body>
</html>

