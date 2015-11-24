<?php

include('../core/init.inc.php');
include('init.inc.php');

$errors = array();
if(isset($_POST['urltitle'], $_POST['url'])){
	if(empty($_POST['urltitle']) || empty($_POST['url'])){
		$errors[] = 'All fields must be filled out.';
	}
	if(!filter_var($_POST['url'], FILTER_VALIDATE_URL)){
		$errors[] = 'Not a valid URL.';
	}
	if(empty($errors)){
		add_post($id, get_alias($id), $_POST['urltitle'], $_POST['url'], 1);
		header('Location: posts.php');
		die();
	}
}

if(isset($_POST['title'], $_POST['code'])){
  if(empty($_POST['title']) || empty($_POST['code'])){
    $errors[] = 'All fields must be filled out.';
  }
  if(empty($errors)){
	  add_post($id, get_alias($id), $_POST['title'], bbcode($_POST['code']));
	  header('Location: posts.php');
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
        color:black;
      }

      #privacy{
        color:#4d4d4d;border-bottom: 1px dotted;
      }

      .form-post {
        max-width: 800px;
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
      .form-url {
	      text-align: center;
	      padding: 19px 29px 29px;
	      max-width: 768px;
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
      h2#or{
	      text-align: center;
	      
      }
      .form-post .form-post-heading,
      .form-post .checkbox {
        margin-bottom: 10px;
      }
      .form-post input[type="text"],
      .form-post input[type="password"] {
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
      
      <form class="form-post" id='formling' action='#' method='post'>
        <p><input type="text" class="input-large" placeholder="Title" name='title' value='<?php if(isset($_POST['title'])){ echo html_escape($_POST['title']); } ?>' required></p>
        <p><small>BBCode is supported in posting.</small></p>
        <p><textarea style='width:800px;height:350px;' name='code' required></textarea></p>
        <p><button class="btn btn-large btn-primary" type="submit">Post</button>&emsp;<button id='preview' type='submit' formaction='preview.php' formtarget='_blank' class="btn btn-large btn-success">Preview</button>&emsp;<a class="btn btn-large btn-warning" href="upload.php" target="_blank">Upload File</a></p>
      </form>
      <h2 id="or">OR</h2>
      <form class="form-url form-inline" id="formling" action="#" method="post">
      	<input type="text" name="urltitle" class="form-control" placeholder="Title" required>
      	<input type="url" name="url" class="form-control" placeholder="URL" required>
      	<button class="btn btn-primary" type="submit">Post</button>
      </form>
      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2013&emsp;<a href="/privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="/report.php" id='privacy'>Report a Bug</a></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="minified/jquery.sceditor.bbcode.min.js"></script>
    <script type="text/javascript">$(function(){$("textarea").sceditor({plugins:"bbcode",style:"/assets/css/bootstrap.min.css",toolbarExclude:"emoticon,maximize,print,font",parserOptions: { breakAfterBlock: false }})})</script>
    <script type="text/javascript">$.sceditor.command.set("image",{exec:function(e){var t=this;var n=$('<div><label for="link">'+t._("URL:")+'</label> <input type="text" id="image" value="http://" /></div><div><input type="button" class="button" value="'+t._("Insert")+'" /></div>');n.find(".button").click(function(e){var r=n.find("#image").val();if(r&&r!=="http://")t.wysiwygEditorInsertHtml('<img src="'+r+'" />');t.closeDropDown(true);e.preventDefault()});t.createDropDown(e,"insertimage",n)}});$.sceditorBBCodePlugin.bbcode.set("img",{format:function(e,t){if(typeof e.attr("data-sceditor-emoticon")!=="undefined")return t;return"[img]"+e.attr("src")+"[/img]"},html:'<img src="{0}" />'})</script>
  </body>
</html>
