<?php

include('../core/init.inc.php');
include('init.inc.php');

if($admin === false){
  die('You do not have the permissions to view this webpage.');
}

$errors = array();
if(isset($_POST['email'], $_POST['account_type'])){
	if(empty($_POST['account_type'])){
		$errors[] = 'All fields must be filled out.';
	}
	if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
	    $errors[] = 'The email address you entered does not appear to be valid.';
	}
	if(email_exists($_POST['email'])){
		$errors[] = 'The email address you entered is already associated with an account.';
	}
	if(empty($errors)){
		if(send_invite($_POST['email'], $_POST['account_type']) === false){
			die('Invalid rank type.');
		}
		header('Location: add_user.php?success');
	}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le styles -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/elusive-webfont.min.css">
    <link rel="stylesheet" href="minified/themes/default.min.css" type="text/css" media="all" />
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
        background: #eee;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }

      #privacy{
        color:#4d4d4d;border-bottom: 1px dotted;
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

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 960px;
        background:rgba(255, 255, 255, 0.7);
        padding:20px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      .nav{
        margin-bottom: 0;
        margin-top:-10px;
      }

      .nav > li > a{
        color:black;
      }

      .nav > li > a > i{
        text-align: center;
        display: block;
        color:black;
        font-size: 2em;
        line-height: 1.25em;
      }

      .nav > li.active > a > i{
        color:white;
      }

      h3.muted{
        font-size: 7em;
      }

      #navbarhr{
        margin-top:40px;
      }

      textarea{
        width:auto;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 10%;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }

      .statcrop > p{
        text-align: center;
      }

      .statcrop > p > a{
        margin-left: 10px;
        margin-right: 10px; 
      }
.footer{
        font-size:10px;
      }
    </style>

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

    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <?=$nav?>
          <?=$rightnav?>
        </ul>
        <h3 class="muted"><a href='/' style='color:#999999;font-family:"Times New Roman", Times;'><?php echo $siteinfo['title']; ?></a><a style='color:#999999;font-size:20px;'>staff</a></h3>
      </div>

      <hr id='navbarhr'>
      <?php
      	if(isset($_GET['success'])){
      		echo 'An invitation has been sent to the given email.';
      	}
      ?>
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
      <form class="form-signin" id='formling' action='#' method='post'>
        <h3>Register A New Account</h3>
        <br />
        <input type="text" class="input-block-level" placeholder="Email address" name='email' value='<?php if(isset($_POST['email'])){ echo html_escape($_POST['email']); } ?>' required>
        <h5>Account Type</h5>
        <div class='well'>
	        <label class="radio">
			  <input type="radio" class='school' name="account_type" value="school" checked>
			  School Account
			</label>
	        <label class="radio">
			  <input type="radio" class='writer' name="account_type" value="writer">
			  Writer Account
			</label>
			<label class="radio">
			  <input type="radio" class='admin' name="account_type" value="admin">
			  Admin Account
			</label>
		</div>
		<div class="alert warns"><strong>Warning!</strong> Admins and Writers are unable to participate in the competition!</div>
        <button class="btn btn-large btn-primary" type="submit">Send Invite</button>
      </form>
      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="/privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="/report.php" id='privacy'>Report a Bug</a></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="minified/jquery.sceditor.bbcode.min.js"></script>
    <script type="text/javascript">$(function(){$("textarea").sceditor({plugins:"bbcode",style:"/assets/css/bootstrap.min.css",toolbarExclude:"emoticon,maximize,print"})})</script>
    <script type="text/javascript">$(function(){$("div.warns").hide();$("input.school").click(function(){$("div.warns").hide()});$("input.writer").click(function(){$("div.warns").show()});$("input.admin").click(function(){$("div.warns").show()})})</script>
    <script type="text/javascript">$.sceditor.command.set("image",{exec:function(e){var t=this;var n=$('<div><label for="link">'+t._("URL:")+'</label> <input type="text" id="image" value="http://" /></div><div><input type="button" class="button" value="'+t._("Insert")+'" /></div>');n.find(".button").click(function(e){var r=n.find("#image").val();if(r&&r!=="http://")t.wysiwygEditorInsertHtml('<img src="'+r+'" />');t.closeDropDown(true);e.preventDefault()});t.createDropDown(e,"insertimage",n)}});$.sceditorBBCodePlugin.bbcode.set("img",{format:function(e,t){if(typeof e.attr("data-sceditor-emoticon")!=="undefined")return t;return"[img]"+e.attr("src")+"[/img]"},html:'<img src="{0}" />'})</script>
  </body>
</html>