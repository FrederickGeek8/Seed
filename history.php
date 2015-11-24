<?php
include('core/init.inc.php');

// Can be removed as of PHP 5.5.0
if( !function_exists( 'array_column' ) ):
  function array_column( array $input, $column_key, $index_key = null ) {
    $result = array();
    foreach( $input as $k => $v )
        $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
    
    return $result;
  }
endif;

$user = user_lookup($_SESSION['id']);
$user['unvalue'] = $user['value'];
$user['value'] = number_format($user['value'], 2);
$index = -1; $subindex = -1; $subsubindex = -1;
$stocks = get_user_stocks($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['description']; ?>">
    <link rel="stylesheet" href="/assets/css/dark-hive/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen" charset="utf-8">
	 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
   <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript">function number_format(e,t,n,r){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var i=!isFinite(+e)?0:+e,s=!isFinite(+t)?0:Math.abs(t),o=typeof r==="undefined"?",":r,u=typeof n==="undefined"?".":n,a="",f=function(e,t){var n=Math.pow(10,t);return""+Math.round(e*n)/n};a=(s?f(i,s):""+Math.round(i)).split(".");if(a[0].length>3){a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)}if((a[1]||"").length<s){a[1]=a[1]||"";a[1]+=(new Array(s-a[1].length+1)).join("0")}return a.join(u)}function float_fix(e){var t=Math.round(e*100)/100;return t.toFixed(2)};<?php if(empty($stocks) === false){ $stocksym = implode('\'%2C\'', array_column($stocks, 'stock')); ?>
  $(function() {
  $( "#dialog" ).dialog({
  autoOpen: false,
  open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
    modal: true,
    buttons: {
      Reload: function() {
        location.reload();
      }
    }
  });
  });
  $(document).ready(function() {
  function alertr(message){ $( "#dialog" ).dialog( "open" ); $("#dialogtext").text(message); var a=document.title;setInterval(function(){document.title=document.title==a?"Error":a},1500); }
  $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20Change%2C%20ChangeinPercent%20from%20yahoo.finance.quotes%20where%20symbol%20in%20('<?=$stocksym?>')&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&diagnostics=true")
      .done(function (data) {
      var portfolio = 0;
      var overallgain = 0;
      <?php foreach ($stocks as $stock) { $subindex++; ?>
      var current<?=$subindex?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.LastTradeWithTime.split(' - ');
      current<?=$subindex?> = current<?=$subindex?>[1].replace(/(<([^>]+)>)/ig,"");
      $('#current-price-<?=$subindex?>').html("Current Price: <b>$"+current<?=$subindex?>+"</b>/share");
      var change<?=$subindex?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.ChangeinPercent.split(' - ');
      $('#change-<?=$subindex?>').text(data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.Change + " (" + change<?=$subindex?>[1] + ")");
      var value<?=$subindex?> = number_format(float_fix(current<?=$subindex?> * <?=$stock['amount']?>), 2);
      var csp<?=$subindex?> = current<?=$subindex?> - <?=$stock['price']?>;
      var csppercent<?=$subindex?> = (csp<?=$subindex?> * <?=$stock['amount']?>) / (<?=$stock['amount']?> * <?=$stock['price']?>);
      overallgain = overallgain + (csp<?=$subindex?> * <?=$stock['amount']?>);
      if(csp<?=$subindex?> > 0){
        csp<?=$subindex?> = "Return: <a style='color:green;'>+"+number_format(float_fix(csp<?=$subindex?>), 2)+" (+"+number_format(float_fix(csppercent<?=$subindex?>), 2)+"%)</a>";
      }else{
        csp<?=$subindex?> = "Return: <a style='color:red;'>"+number_format(float_fix(csp<?=$subindex?>), 2)+" ("+number_format(float_fix(csppercent<?=$subindex?>), 2)+"%)</a>";
      }
      $('#csp-<?=$subindex?>').html(csp<?=$subindex?>);
      $('#current-value-<?=$subindex?>').text(value<?=$subindex?>);
      portfolio = portfolio + (current<?=$subindex?> * <?=$stock['amount']?>);
      <?php } ?>
      $("#portval").text("$"+number_format(float_fix(portfolio), 2)); $("#gain").text("$"+number_format(float_fix(overallgain), 2));
  })
  .fail(function (err) {
    if(typeof failed == 'undefined'){
    if(err.status == "500"){
          alertr("Yahoo is having issue. Please reload and try back later.");
        }else{
          alertr("Request Failed. Please reload the page.");
            
        }
        failed = 'failed';
  }
  });
    setInterval(get_stats, 15000);

    function get_stats() {
        if(typeof failed == 'undefined'){
        $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20Change%2C%20ChangeinPercent%20%20from%20yahoo.finance.quotes%20where%20symbol%20in%20('<?=$stocksym?>')&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&diagnostics=true")
            .done(function (data) {
        delete failed;
        portfolio = 0;
        overallgain = 0;

              <?php foreach ($stocks as $stock) { $subsubindex++; ?>
              var current<?=$subsubindex?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.LastTradeWithTime.split(' - ');
              current<?=$subsubindex?> = current<?=$subsubindex?>[1].replace(/(<([^>]+)>)/ig,"");
              $('#current-price-<?=$subsubindex?>').html("Current Price: <b>$"+current<?=$subsubindex?>+"</b>/share");
              var change<?=$subsubindex?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.ChangeinPercent.split(' - ');
              $('#change-<?=$subsubindex?>').text(data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.Change + " (" + change<?=$subsubindex?>[1] + ")");
              var value<?=$subindex?> = number_format(float_fix(current<?=$subsubindex?> * <?=$stock['amount']?>), 2);
              var csp<?=$subindex?> = current<?=$subsubindex?> - <?=$stock['price']?>;
              var csppercent<?=$subindex?> = (csp<?=$subindex?> * <?=$stock['amount']?>) / (<?=$stock['amount']?> * <?=$stock['price']?>);
              overallgain = overallgain + (csp<?=$subindex?> * <?=$stock['amount']?>);

              if(csp<?=$subindex?> > 0){
                csp<?=$subindex?> = "Return: <a style='color:green;'>+"+number_format(float_fix(csp<?=$subindex?>), 2)+" (+"+number_format(float_fix(csppercent<?=$subindex?>), 2)+"%)</a>";
              }else{
                csp<?=$subindex?> = "Return: <a style='color:red;'>"+number_format(float_fix(csp<?=$subindex?>), 2)+" ("+number_format(float_fix(csppercent<?=$subindex?>), 2)+"%)</a>";
              }
              $('#csp-<?=$subsubindex?>').html(csp<?=$subindex?>);
              $('#current-value-<?=$subsubindex?>').text(value<?=$subindex?>);
              portfolio = portfolio + (current<?=$subsubindex?> * <?=$stock['amount']?>);
              <?php } ?>
      $("#portval").text("$"+number_format(float_fix(portfolio), 2)); $("#gain").text("$"+number_format(float_fix(overallgain), 2));
          })
            .fail(function (err) {
        if(typeof failed == 'undefined'){
    if(err.status == "500"){
          alertr("Yahoo is having issue. Please reload and try back later.");
        }else{
          alertr("Request Failed. Please reload the page.");
            
        }
        failed = 'failed';
        }
          });
          }else{
            $('.stat').text("Failed!");
          }
    }
  });<?php }else{ ?>$(document).ready(function() { $("#portval").text("$0"); $("#gain").text("$0"); });<?php } ?></script>








    </script>
    <!-- Le styles -->
    <style type="text/css">
    body {
      background: url("/assets/img/new-york-1.jpg");
      <?php if($android){ ?>
      background:#1e2f47;
      <?php } ?>
    }
    </style>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      .body{text-shadow:1px 1px 1px rgba(0,0,0,.5);}
    .personal{ border-bottom: 1px solid white;display: inline-block; text-align: center; width:fit-content; margin: 15px; }
    .personal h2{ font-size:1.7em; } .personal h3{ font-size:1.5em; }
    @media(max-width: 1375px) { .personal h2{ font-size:1.5em; } .personal h3{ font-size:1.3em; } }
    .table {text-shadow:none;}
    table {color:black;background: white;}
    </style>

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
      <div class="body" style="padding:20px;">
      <h1>Previous Transactions</h1>
      <table class="table table-striped table-bordered">
        <tr>
          <th>Date</th>
          <th>Symbol</th>
          <th>Type</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
        <?php
        $json = json_decode(file_get_contents("/var/www/core/history/{$user['id']}/history"), true);
        foreach ($json["Logs"] as $log) {?>
        <tr><td style="width:200px"><?=date('d/m/y h:i A', $log['Timestamp'])?></td><td><?=$log['Symbol']?></td><td><?=$log["Operation"]?></td><td><?=$log["Quantity"]?></td><td><?=$log["Price"]?></td><td><?=$log["Total"]?></td></tr>
        <?php }
        ?>
      </table>
      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>