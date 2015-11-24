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
    	background-color: #84796e;
      background: url("/assets/img/07_unknown_artist_charlie_chaplin_and_douglas_fairbanks_1918-1.jpg");
      <?php if($android){ ?>
      background:url("http://marketdream.org/assets/img/use_your_illusion.png");
      background-repeat: repeat;
      background-size: scroll;  
      <?php } ?>
    }

	 .statcrop{overflow:hidden;text-align:center}.statcrop img{max-width:100%;margin:0 auto}.statcrop>p>a{margin-left:10px;margin-right:10px;color:#e6e6e6;text-shadow: 1px 1px 3px black;}
    </style>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background-color:#84796e;}</style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/cupertino/jquery-ui.css">

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

      <form class="form-inline" method='get' action='search' style='text-align:center;'>
        <label for="search">Search for a Stock:</label>
        <input type="text" class="form-control search-query" name="q" id='search' autocomplete="off" placeholder="Stock Name or Symbol" />
        <button type="submit" class="btn">Search</button>
      </form>
      <h2 style='text-align:center;'><i class='icon-graph'></i> Today's Stats</h2><br><div class='row-fluid'><div class='col-md-6'><!-- Dow Jones--><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c11"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c12"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c13"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c14"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=3m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c15"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c16"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c17"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div><div class="tab-pane" id="c18"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eDJI&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Dow Jones Industrial Average' /></div></div><p><a href="#c11" data-toggle="tab">1d</a><a href="#c12" data-toggle="tab">5d</a><a href="#c13" data-toggle="tab">1m</a><a href="#c14" data-toggle="tab">3m</a><a href="#c15" data-toggle="tab">6m</a><a href="#c16" data-toggle="tab">1y</a><a href="#c17" data-toggle="tab">2y</a><a href="#c18" data-toggle="tab">5y</a></p></div><br><!-- NASDAQ --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c21"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c22"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c23"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c24"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=3m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c25"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c26"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c27"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div><div class="tab-pane" id="c28"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eIXIC&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NASDAQ Composite' /></div></div><p><a href="#c21" data-toggle="tab">1d</a><a href="#c22" data-toggle="tab">5d</a><a href="#c23" data-toggle="tab">1m</a><a href="#c24" data-toggle="tab">3m</a><a href="#c25" data-toggle="tab">6m</a><a href="#c26" data-toggle="tab">1y</a><a href="#c27" data-toggle="tab">2y</a><a href="#c28" data-toggle="tab">5y</a></p></div><br><!-- S&P 500 --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c31"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c32"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c33"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c34"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=3m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c35"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c36"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c37"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div><div class="tab-pane" id="c38"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGSPC&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='S&P 500' /></div></div><p><a href="#c31" data-toggle="tab">1d</a><a href="#c32" data-toggle="tab">5d</a><a href="#c33" data-toggle="tab">1m</a><a href="#c34" data-toggle="tab">3m</a><a href="#c35" data-toggle="tab">6m</a><a href="#c36" data-toggle="tab">1y</a><a href="#c37" data-toggle="tab">2y</a><a href="#c38" data-toggle="tab">5y</a></p></div><!-- FTSE 100 --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c41"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c42"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c43"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c44"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=3m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c45"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c46"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c47"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div><div class="tab-pane" id="c48"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFTSE&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='FTSE 100' /></div></div><p><a href="#c41" data-toggle="tab">1d</a><a href="#c42" data-toggle="tab">5d</a><a href="#c43" data-toggle="tab">1m</a><a href="#c44" data-toggle="tab">3m</a><a href="#c45" data-toggle="tab">6m</a><a href="#c46" data-toggle="tab">1y</a><a href="#c47" data-toggle="tab">2y</a><a href="#c48" data-toggle="tab">5y</a></p></div><!-- CAC 40 --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c51"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c52"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c54"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c54"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c55"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c56"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c57"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div><div class="tab-pane" id="c58"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eFCHI&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='CAC 40' /></div></div><p><a href="#c51" data-toggle="tab">1d</a><a href="#c52" data-toggle="tab">5d</a><a href="#c53" data-toggle="tab">1m</a><a href="#c54" data-toggle="tab">4m</a><a href="#c55" data-toggle="tab">6m</a><a href="#c56" data-toggle="tab">1y</a><a href="#c57" data-toggle="tab">2y</a><a href="#c58" data-toggle="tab">5y</a></p></div></div><div class='col-md-6'><!-- DAX --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c61"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c62"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c64"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c64"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c65"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c66"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c67"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div><div class="tab-pane" id="c68"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eGDAXI&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='DAX' /></div></div><p><a href="#c61" data-toggle="tab">1d</a><a href="#c62" data-toggle="tab">5d</a><a href="#c63" data-toggle="tab">1m</a><a href="#c64" data-toggle="tab">4m</a><a href="#c65" data-toggle="tab">6m</a><a href="#c66" data-toggle="tab">1y</a><a href="#c67" data-toggle="tab">2y</a><a href="#c68" data-toggle="tab">5y</a></p></div><br><!-- Mexican Bolsa IPC --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c71"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c72"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c74"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c74"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c75"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c76"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c77"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div><div class="tab-pane" id="c78"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eMXX&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Mexican Bolsa IPC' /></div></div><p><a href="#c71" data-toggle="tab">1d</a><a href="#c72" data-toggle="tab">5d</a><a href="#c73" data-toggle="tab">1m</a><a href="#c74" data-toggle="tab">4m</a><a href="#c75" data-toggle="tab">6m</a><a href="#c76" data-toggle="tab">1y</a><a href="#c77" data-toggle="tab">2y</a><a href="#c78" data-toggle="tab">5y</a></p></div><br><!-- Brazil Bovespa --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c81"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c82"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c84"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c84"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c85"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c86"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c87"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div><div class="tab-pane" id="c88"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eBVSP&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Brazil Bovespa' /></div></div><p><a href="#c81" data-toggle="tab">1d</a><a href="#c82" data-toggle="tab">5d</a><a href="#c83" data-toggle="tab">1m</a><a href="#c84" data-toggle="tab">4m</a><a href="#c85" data-toggle="tab">6m</a><a href="#c86" data-toggle="tab">1y</a><a href="#c87" data-toggle="tab">2y</a><a href="#c88" data-toggle="tab">5y</a></p></div><!-- Shanghai Composite --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c91"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c92"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c94"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c94"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c95"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c96"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c97"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div><div class="tab-pane" id="c98"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=000001.SS&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Shanghai Composite' /></div></div><p><a href="#c91" data-toggle="tab">1d</a><a href="#c92" data-toggle="tab">5d</a><a href="#c93" data-toggle="tab">1m</a><a href="#c94" data-toggle="tab">4m</a><a href="#c95" data-toggle="tab">6m</a><a href="#c96" data-toggle="tab">1y</a><a href="#c97" data-toggle="tab">2y</a><a href="#c98" data-toggle="tab">5y</a></p></div><!-- Hang Seng --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c101"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c102"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c104"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c104"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c105"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c106"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c107"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div><div class="tab-pane" id="c108"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eHSI&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='Hang Seng' /></div></div><p><a href="#c101" data-toggle="tab">1d</a><a href="#c102" data-toggle="tab">5d</a><a href="#c103" data-toggle="tab">1m</a><a href="#c104" data-toggle="tab">4m</a><a href="#c105" data-toggle="tab">6m</a><a href="#c106" data-toggle="tab">1y</a><a href="#c107" data-toggle="tab">2y</a><a href="#c108" data-toggle="tab">5y</a></p></div></div><br><!-- NIKKEI 225 --><div class='statcrop'><div class="tab-content"><div class="tab-pane active" id="c111"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=1d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c112"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=5d&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c114"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=1m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c114"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=4m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c115"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=6m&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c116"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=1y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c117"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=2y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div><div class="tab-pane" id="c118"><img style='display:block;' src='http://chart.finance.yahoo.com/z?s=%5eN225&t=5y&q=l&l=on&z=s&lang=en-US&region=US#.png' title='NIKKEI 225' /></div></div><p><a href="#c111" data-toggle="tab">1d</a><a href="#c112" data-toggle="tab">5d</a><a href="#c113" data-toggle="tab">1m</a><a href="#c114" data-toggle="tab">4m</a><a href="#c115" data-toggle="tab">6m</a><a href="#c116" data-toggle="tab">1y</a><a href="#c117" data-toggle="tab">2y</a><a href="#c118" data-toggle="tab">5y</a></p></div><small style='padding-top:-50px;'>Charts brought to you by <a target="_blank" href='http://finance.yahoo.com/'>Yahoo Finance</a></small></div>
      <hr>
      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
      </div>

   </div>
   </div> <!-- /container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> 
    <script type="text/javascript">$("#search").autocomplete({
    source: function (request, response) {
        
        // faking the presence of the YAHOO library bc the callback will only work with
        // "callback=YAHOO.Finance.SymbolSuggest.ssCallback"
        var YAHOO = window.YAHOO = {Finance: {SymbolSuggest: {}}};
        
        YAHOO.Finance.SymbolSuggest.ssCallback = function (data) {
            var mapped = $.map(data.ResultSet.Result, function (e, i) {
                return {
                    label: e.symbol + ' (' + e.name + ')',
                    value: e.symbol
                };
            });
            response(mapped);
        };
        
        var url = [
            "http://d.yimg.com/autoc.finance.yahoo.com/autoc?",
            "query=" + request.term,
            "&callback=YAHOO.Finance.SymbolSuggest.ssCallback"];

        $.getScript(url.join(""));
    },
    minLength: 2
});</script>
    <?php if(isset($_COOKIE['alert_close'])){ ?><script type="text/javascript">$(".alert").alert('close')</script><?php } ?>
  </body>
</html>
