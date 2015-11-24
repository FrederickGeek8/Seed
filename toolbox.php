<?php

include('core/init.inc.php');

$errors = array();
if(isset($_GET['p'])){
  if(valid_post($_GET['p']) === false){
    $errors[] = 'Invalid post id';
  }

  if(empty($errors)){
    $post = get_post($_GET['p']);
  }
}else{
  if(empty($errors)){
    $posts = get_posts();
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
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
    <style>

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
    a{color:inherit}.post a{color:white;}#desc a, #desc {color:black;}.posts h1 a{color:black;font-family: 'Roboto', sans-serif;font-weight:100;font-size:3.5rem;}.posts h1 a:hover{text-decoration: none;border-bottom:1px solid black;}.post a:hover,.post a:focus{color:white ;text-decoration:none}.post>a:link{-ms-word-break:break-all;word-break:break-all;word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;}a:hover,a:focus{color:inherit;text-decoration:inherit}a:link{-ms-word-break:break-all;word-break:break-all;word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;}.appwrapper{margin-left:5%;}.app{display:inline-block;text-align:center;font-size:10px;white-space: nowrap;color:black;}.app img{border-radius: 10px;margin:15px 15px 5px 15px;}.app img:hover{-webkit-filter:contrast(75%);cursor:pointer  }

    </style>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>.post-body{padding:25px;background:white;color:black;box-shadow:0 1px 2px #aaa;border-radius:3px;} .post-header{text-align:center;} .post a{color:black;}.post a:hover{color:black;}</style>

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
      <!-- Main component for a primary marketing message or call to action -->
      <div class="body">
        <?php if(isset($_GET['p']) && empty($errors)){ ?>
        <div class='post'>
          <div class='post-header'><h1><?=$post['title']?></h1><h5>&emsp;<i class='icon-user'></i> Posted by <?=$post['user']?> on <?=$post['date']?></h5></div>
          <br />
          <div class='post-body'><?=$post['body']?></div>
        </div>
        <?php }elseif(empty($errors)){ if(isset($_GET['view_all']) === false){ ?><div class="row"><div class="col-md-6"><?php foreach($posts AS $post){ ?>
        <div class='posts well'><h1><a href='<?php if($post['url'] == 0){ ?>toolbox.php?p=<?=$post['id']?><?php }else{ echo $post['body']; } ?>'><?=$post['title']?></a></h1><?php if($post['url'] == 0){ ?><p class="post" id="desc"><?=$post['preview']?></p><?php } ?></div>
        <?php } ?><p><a href="?view_all">View All...</a></p></div><div class="col-md-6 well"><div class="appwrapper"><div class="app"><a href="http://money.cnn.com/" target="_blank"><img title="CNN Money" src="assets/img/links/CNN-Money-Logo.png" width="64" /></a><p>CNN Money</p></div><div class="app"><a href="http://www.bbc.com/news/" target="_blank"><img title="BBC" src="assets/img/links/bbc_icon.png" width="64" /></a><p>BBC</p></div><div class="app"><a href="http://online.wsj.com/home-page" target="_blank"><img title="WSJ" src="assets/img/links/wsj_app_icon.png" width="64" /></a><p>WSJ</p></div><div class="app"><a href="http://www.nytimes.com/" target="_blank"><img title="NYTimes" src="assets/img/links/5.png" width="64" /></a><p>NYTimes</p></div><div class="app"><a href="http://www.zerohedge.com/" target="_blank"><img title="Zero Hedge" src="assets/img/links/zero-hedge.png" width="64" /></a><p>Zero Hedge</p></div><div class="app"><a href="http://bigcharts.marketwatch.com/" target="_blank"><img title="BigCharts" src="assets/img/links/big-charts.png" width="64" /></a><p>BigCharts</p></div><div class="app"><a href="http://www.fool.com/investing/" target="_blank"><img title="The Motley Fool" src="assets/img/links/jpeg.jpeg" width="64" /></a><p>The Motley Fool</p></div><div class="app"><a href="http://www.investmentnews.com/" target="_blank"><img title="InvestmentNews" src="assets/img/links/jpeg-1.jpeg" width="64" /></a><p>InvestmentNews</p></div><div class="app"><a href="http://moneymorning.com" target="_blank"><img title="Money Morning" src="assets/img/links/moneymorning.png" width="64" /></a><p>Money Morning</p></div><div class="app"><a href="http://www.briefing.com/investor/calendars/stock-splits/" target="_blank"><img title="Stock Splits" src="assets/img/links/Briefing_FB_Like_Logo.GIF" width="64" /></a><p>Stock Splits</p></div></div></div></div><?php }else{ foreach($posts AS $post){ ?>
        <div class='posts well'><h1><a href='<?php if($post['url'] == 0){ ?>toolbox.php?p=<?=$post['id']?><?php }else{ echo $post['body']; } ?>'><?=$post['title']?></a></h1><?php if($post['url'] == 0){ ?><p class="post" id="desc"><?=$post['preview']?></p><?php } ?></div>
        <?php } ?><?php } }else{?>
        <ul>
        <?php foreach($errors AS $error){ echo "<li>{$error}</li>"; } ?>
        </ul>
        <?php } ?>
        <div class="footer">
          <hr>
          <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
        </div>
      </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
	<?php if($newpost){ ?><script type="text/javascript">document.cookie="viewd=seen;";</script><?php } ?>
  </body>
</html>