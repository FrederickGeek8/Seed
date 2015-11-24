<?php

include('../core/init.inc.php');
include('init.inc.php');

if(isset($_GET['del'])){
  $id = (int)$_GET['del'];
  mysql_query("DELETE FROM `posts` WHERE `post_id` = '{$id}'");
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
      <h1>Posts</h1>
      <div class='well'>
      <?php $posts = get_posts(); foreach ($posts as $post) { ?>
      <p><?=$post['title']?>&emsp;<a href="edit_post.php?p=<?=$post['id']?>" class='btn btn-warning btn-mini'>Edit</a> <a href="#" id='<?=$post['id']?>' class='btn btn-danger btn-mini' data-toggle="popover" title data-content="<p>Are you sure you want to delete this post?</p><p><a href='posts.php?del=<?=$post['id']?>' class='btn btn-danger btn-mini'>Yes!</a>&emsp;<a href='#' onClick=&quot;$('#<?=$post['id']?>').popover('hide')&quot; class='btn btn-warning btn-mini'>No!</a></p>" data-original-title="Confirm">Delete</a></p>
      <?php } ?>
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
    <script type="text/javascript">if (window.location.href.match(/\#buy/)){ $('#buy').modal('show'); }</script>
    <script type="text/javascript">if (window.location.href.match(/\#sell/)){ $('#sell').modal('show'); }</script>
    <script type="text/javascript"><?php foreach($posts AS $post){ ?>$('#<?=$post['id']?>').popover({html: true});<?php } ?></script>

  </body>
</html>
