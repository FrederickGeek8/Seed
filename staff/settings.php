<?php

include('../core/init.inc.php');
include('init.inc.php');

$errors = array();
if(isset($_POST['sitename'], $_POST['sitedesc'], $_POST['fullname'], $_POST['terms'])){
  if(empty($_POST['fullname'])){
	  $errors[] = 'You must specify a full name';
  }
  if(empty($_POST['sitename'])){
	  $errors[] = 'You must specify a sitename.';
  }
  if(empty($_POST['sitedesc'])){
	  $errors[] = 'You must specify a site description.';
  }
  if(empty($_POST['terms'])){
	  $errors[] = 'You must specify terms & conditions.';
  }
  if(empty($errors)){
	$sitename = html_escape($_POST['sitename']);
	$sitefullname = html_escape($_POST['fullname']);
	$sitedesc = str_replace('{{sitetitle}}', $sitename, html_escape($_POST['sitedesc']));
	$siteterms = str_replace('{{sitetitle}}', $sitename, html_escape($_POST['terms']));
	$data = '<?php

$siteinfo[\'title\'] = "'.$sitename.'";

$siteinfo[\'description\'] = "'.$sitedesc.'";

$siteinfo[\'fullname\'] = "'.$sitefullname.'";

$siteinfo[\'terms\'] = "'.$siteterms.'";

?>';
	file_put_contents('../core/inc/globals.inc.php', $data);
	header('Location: settings.php');
	die();
  }
}

if(isset($_POST['alias'])){
  if(empty($_POST['alias'])){
	$errors[] = 'You must specify an alias';
  }
  if(empty($errors)){
	$alias = mysql_real_escape_string(html_escape($_POST['alias']));
	if($admin){
	  mysql_query("UPDATE `admins` SET `alias` = '{$alias}' WHERE `user_id` = '{$id}'");
	  mysql_query("UPDATE `posts` SET `user_name` = '{$alias}' WHERE `user_id` = '{$id}'");
	}else{
	  mysql_query("UPDATE `writers` SET `alias` = '{$alias}' WHERE `user_id` = '{$id}'");
	  mysql_query("UPDATE `posts` SET `user_name` = '{$alias}' WHERE `user_id` = '{$id}'");
	}
	header('Location: settings.php');
	die();
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
	<style type="text/css">
	  body {
		padding-top: 20px;
		padding-bottom: 40px;
		background: #eee;
		background-repeat: no-repeat;
		background-size: cover;
		background-position: center;
		background-attachment: fixed;
		color:black;
	  }

	  #privacy{
		color:#4d4d4d;border-bottom: 1px dotted;
	  }

	  #newslink:hover, #privacy:hover{
		text-decoration: none;
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

	  textarea{
		width:auto;
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
	  if(empty($errors) === false){
		?><ul><?php foreach ($errors as $error) {
			echo "<li>{$error}</li>";
		}
		?></ul><?php
	  }
	  ?>
	  <h1>Alias</h1>
	  <?php
	  if($admin){
		$sql = mysql_query("SELECT `alias` FROM `admins` WHERE `user_id` = '{$id}'");
	  }else{
		$sql = mysql_query("SELECT `alias` FROM `writers` WHERE `user_id` = '{$id}'");
	  }
	  $alias = mysql_result($sql, 0);
	  $siteinfo['description'] = str_replace($siteinfo['title'], '{{sitetitle}}', $siteinfo['description']);
	  $siteinfo['terms'] = str_replace($siteinfo['title'], '{{sitetitle}}', $siteinfo['terms']);
	  ?>
	  <div class='well'><form action='#' method='post'><input type='text' name='alias' placeholder='Alias' value='<?=$alias?>' required><p><button type='submit' class='btn btn-warning'>Update</button></p></form></div>
	  <?php if($admin){ ?>
	  <h1>Site Settings</h1>
	  <div class='well'><form action='#' method='post'><input type='text' name='sitename' placeholder='Site Shortname' value='<?=$siteinfo['title']?>' required><p><input type='text' name='fullname' placeholder='Site Fullname' value="<?=$siteinfo['fullname']?>" required></p><p><small>Please use {{sitetitle}} in the body for the website name.</small></p><p><textarea name='sitedesc' rows='15' cols='75' required><?=$siteinfo['description']?></textarea></p><hr><h2>Terms & Conditions</h2><p><small>Please use {{sitetitle}} in the terms & conditions for the website name.</small></p><p><textarea name='terms' rows='15' cols='75' required><?=$siteinfo['terms']?></textarea></p><p><button type='submit' class='btn btn-warning'>Update</button></p></form></div>
	  <?php } ?>
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
	<script type="text/javascript">if (window.location.href.match(/\#buy/)){ $('#buy').modal('show'); }</script>
	<script type="text/javascript">if (window.location.href.match(/\#sell/)){ $('#sell').modal('show'); }</script>
	<?php if(isset($_COOKIE['alert_close'])){ ?><script type="text/javascript">$(".alert").alert('close')</script><?php } ?>

  </body>
</html>
