<?php

include('../core/init.inc.php');
include('init.inc.php');

if(isset($_POST['code']) === false){
	die('Invalid use!');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Previewing Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['description']; ?>">

    <!-- Le styles -->
<!--[if lte IE 7]><script src="/assets/elusive-font/lte-ie7.js"></script><![endif]-->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/elusive-webfont.min.css">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
        background: url('http://indogenius.com/wp-content/uploads/2013/05/larger-lecture-hall.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }

      #privacy{
        color:#4d4d4d;border-bottom: 1px dotted;
      }

      #privacy:hover{
        text-decoration: none;
      }
      a{
        color:inherit;
      }
      a:hover,a:focus{color:inherit;text-decoration:inherit;}
      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 960px;
        background:rgba(255, 255, 255, 0.7);
        padding:20px;
      }      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      .post > *{
        max-width: 900px;
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

      .post{
      	background:rgba(255, 255, 255, 0.5);
      	padding:15px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 10%;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }

      @-webkit-keyframes pulse {
		  from {
		    opacity: 0.5;
		  }
		  to {
		    opacity: 0.75;
		  }
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
  	<div style='left:50%;margin-left:-500px;background:white;width:1000px;height:20px;font-size:13px;text-align:center;position:fixed;top:0;-webkit-animation-name: pulse; -webkit-animation-iteration-count: infinite; -webkit-animation-timing-function: ease-in-out; -webkit-animation-direction: alternate; -webkit-animation-duration: 1s;'>Previewing Post</div>
    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li><a href="#"><i class='icon-home'></i>Home</a></li>
          <li class='active'><a href="#"><i class='icon-pencil-alt'></i>Education</a></li>
          <li><a href="#"><i class='icon-graph-alt'></i>Market</a></li>
          <li><a href="#"><i class='icon-star-alt'></i>Leaderboard</a></li>
          <li><a href="#"><i class='icon-briefcase'></i>Portfolio</a></li>
          <li><a href="#"><i class="icon-lock"></i>Logout</a></li>
        </ul>
        <h3 class="muted"><a href='/' style='color:#999999;font-family:"Times New Roman", Times;'><?php echo $siteinfo['title']; ?></a></h3>
      </div>

      <hr id='navbarhr'>
      <div class='post'>
      	<div class='post-header'><h1><?php echo html_escape($_POST['title']); ?></h1><h5>&emsp;<i class='icon-user'></i> Posted by User on <?php echo date('m-d-Y'); ?></h5></div>
      	<br />
      	<div class='post-body'><?php echo bbcode($_POST['code']); ?></div>
      </div>
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

  </body>
</html>