<?php

include('core/init.inc.php');

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

    </style>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background-color:#514d41;}</style>

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
        <div class="jumbotron">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
       viewBox="0 0 500 500" enable-background="new 0 0 500 500" xml:space="preserve" style="width:180px;height:180px;border-radius:8px;margin-bottom:14px;" class="center-block">
            <rect x="-0.5" y="0.5" fill="#212121" stroke="#000000" stroke-miterlimit="10" width="500" height="500"/>
            <g>
              <defs>
                <rect id="SVGID_1_" x="-0.5" y="0.5" width="500.5" height="500.5"/>
              </defs>
              <clipPath id="SVGID_2_">
                <use xlink:href="#SVGID_1_"  overflow="visible"/>
              </clipPath>
              <path  clip-path="url(#SVGID_2_)" fill="none" stroke="#00FF00" stroke-width="7" stroke-miterlimit="10" d="M491.5,-55.5 
                307.6,355 161.8,285.3 -8.5,444.5  " dashoffset="0"/>
            </g>
          </svg>
          <h1><?=$siteinfo['fullname']?></h1>
          <br />
          <p class="lead"><?=$siteinfo['description']?></p>
        </div>
        <h3 style='text-align:center;'><b>News</b></h3>
        <?php

        $feed = simplexml_load_string(file_get_contents("http://www.investopedia.com/rss/stockinvesting.xml"));
        $item = $feed->channel->item;

        foreach ($feed->channel->item as $desc) {
          $desc->description = substr($desc->description, 0, 200)."...";
        }

        ?>
        <div class="row marketing center-block">
          <div class="col-md-6">
            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[0]->link?>'><?=$item[0]->title?></a></b></h4>
            <p><?=$item[0]->description?> <a target="_blank" href='<?=$item[0]->link?>'>Read More</a></p>

            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[2]->link?>'><?=$item[2]->title?></a></b></h4>
            <p><?=$item[2]->description?> <a target="_blank" href='<?=$item[2]->link?>'>Read More</a></p>

            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[4]->link?>'><?=$item[4]->title?></a></b></h4>
            <p><?=$item[4]->description?> <a target="_blank" href='<?=$item[4]->link?>'>Read More</a></p>
            <small>News brought to you by <a target="_blank" href='http://www.investopedia.com/' style="color:white;">Investopedia</a></small>
          </div>

          <div class="col-md-6" style="right:0">
            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[1]->link?>'><?=$item[1]->title?></a></b></h4>
            <p><?=$item[1]->description?> <a target="_blank" href='<?=$item[1]->link?>'>Read More</a></p>

            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[3]->link?>'><?=$item[3]->title?></a></b></h4>
            <p><?=$item[3]->description?> <a target="_blank" href='<?=$item[3]->link?>'>Read More</a></p>

            <h4><b><a style="color:white;border-bottom: 1px grey dotted" id='newslink' target="_blank" href='<?=$item[5]->link?>'><?=$item[5]->title?></a></b></h4>
            <p><?=$item[5]->description?> <a target="_blank" href='<?=$item[5]->link?>'>Read More</a></p>
          </div>

        </div>
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
    <script>$(function() { function drawSVGPaths(a,c,d,e){a=$(a).find("path");$.each(a,function(a){var b=this.getTotalLength();$(this).css({"stroke-dashoffset":-b,"stroke-dasharray":b + " " + b,"-webkit-animation":"dash 5s linear alternate infinite"});$(this).delay(e*a).animate({"stroke-dashoffset":0},{duration:Math.floor(Math.random()*d)+c,easing:"easeInOutQuad"})})}function startSVGAnimation(a){drawSVGPaths(a,750,2E3,25)}startSVGAnimation($("svg")); });</script>
  </body>
</html>
