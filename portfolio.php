<?php
include('core/init.inc.php');

// Can be removed as of PHP 5.5.0
function array_columns( array $input, $column_key, $index_key = null ) {
  $result = array();
  foreach( $input as $k => $v )
      $result[ $index_key ? $v[ $index_key ] : $k ] = urlencode($v[ $column_key ]);
  
  return $result;
}
$user = user_lookup($_SESSION['id']);
$user['unvalue'] = $user['value'];
$user['value'] = number_format($user['value'], 2);
$index = -1; $subindex = -1; $subsubindex = -1;
$stocks = get_user_stocks($_SESSION['id']);
$stocker = get_user_stocks($_SESSION['id'], true);

$totall = array();

foreach ($stocks as $stock) {
  $totall[$stock['stock']] += (int)$stock['amount'];
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
    <link rel="stylesheet" href="/assets/css/dark-hive/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen" charset="utf-8">
    <style type="text/css">
    body {
      background: url("/assets/img/new-york-1.jpg");
      <?php if($android){ ?>
      background:#1e2f47;
      <?php } ?>
    }
    </style>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>body{ background-color:  #3d4a52;};</style>
    <style type="text/css">
      .body{text-shadow:1px 1px 1px rgba(0,0,0,.5);}
    .personal{ border-bottom: 1px solid white;display: inline-block; text-align: center; width:fit-content; margin: 15px; }
    .personal h2{ font-size:1.7em; } .personal h3{ font-size:1.5em; }
    @media(max-width: 1375px) { .personal h2{ font-size:1.5em; } .personal h3{ font-size:1.3em; } }
    .table {text-shadow:none;}
    </style>
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
   <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript">function alertr(message){ $( "#dialog" ).dialog( "open" ); $("#dialogtext").text(message); var a=document.title;setInterval(function(){document.title=document.title==a?"Error":a},1500); }function number_format(e,t,n,r){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var i=!isFinite(+e)?0:+e,s=!isFinite(+t)?0:Math.abs(t),o=typeof r==="undefined"?",":r,u=typeof n==="undefined"?".":n,a="",f=function(e,t){var n=Math.pow(10,t);return""+Math.round(e*n)/n};a=(s?f(i,s):""+Math.round(i)).split(".");if(a[0].length>3){a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)}if((a[1]||"").length<s){a[1]=a[1]||"";a[1]+=(new Array(s-a[1].length+1)).join("0")}return a.join(u)}function float_fix(e){var t=Math.round(e*100)/100;return t.toFixed(2)};<?php if(empty($stocker) === false){ $stocksym = implode('\'%2C\'', array_columns($stocker, 'stock')); ?>
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
    function number_format(e,t,n,r){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var i=!isFinite(+e)?0:+e,s=!isFinite(+t)?0:Math.abs(t),o=typeof r==="undefined"?",":r,u=typeof n==="undefined"?".":n,a="",f=function(e,t){var n=Math.pow(10,t);return""+Math.round(e*n)/n};a=(s?f(i,s):""+Math.round(i)).split(".");if(a[0].length>3){a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)}if((a[1]||"").length<s){a[1]=a[1]||"";a[1]+=(new Array(s-a[1].length+1)).join("0")}return a.join(u)}function float_fix(e){var t=Math.round(e*100)/100;return t.toFixed(2)};
  function alertr(message){ $( "#dialog" ).dialog( "open" ); $("#dialogtext").text(message); var a=document.title;setInterval(function(){document.title=document.title==a?"Error":a},1500); }
  $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20Change%2C%20ChangeinPercent%20from%20yahoo.finance.quotes%20where%20symbol%20in%20('<?=$stocksym?>')&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&diagnostics=true")
      .done(function (data) {
      var portfolio = 0;
      var overallgain = 0;
      console.log(data);
      <?php foreach ($stocker as $stock) { $subindex++; ?>
      var current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.LastTradeWithTime.split(' - ');
      current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>[1].replace(/(<([^>]+)>)/ig,"");
      $('.current-price-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').html("Current Price: <b>$"+current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>+"</b>/share");
      var change<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.ChangeinPercent.split(' - ');
      $('.change-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').text(data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subindex."]"; } ?>.Change + " (" + change<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>[1] + ")");
      var value<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = number_format(float_fix(current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>), 2);
      var csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> - <?=$stock['price']?>;
      var csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = (csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>) / (<?=$stock['amount']?> * <?=$stock['price']?>);
      overallgain = overallgain + (csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>);
      if(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> > 0){
        csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = "Return: <a style='color:green;'>+"+number_format(float_fix(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+" (+"+number_format(float_fix(csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+"%)</a>";
      }else{
        csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = "Return: <a style='color:red;'>"+number_format(float_fix(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+" ("+number_format(float_fix(csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+"%)</a>";
      }
      console.log(overallgain);
      $('.csp-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').html(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>);
      $('.current-value-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').text(value<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>);
      portfolio = portfolio + (current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$totall[$stock['stock']]?>);
      <?php } ?>
      $("#portval").text("$"+number_format(float_fix(portfolio), 2)); $("#gain").text("$"+number_format(float_fix(overallgain), 2));
  })
  .fail(function (err) {
    if(typeof failed == 'undefined'){
      console.log(err.status);
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

              <?php foreach ($stocker as $stock) { $subsubindex++; ?>
              var current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.LastTradeWithTime.split(' - ');
              current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>[1].replace(/(<([^>]+)>)/ig,"");
              $('.current-price-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').html("Current Price: <b>$"+current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>+"</b>/share");
              var change<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.ChangeinPercent.split(' - ');
              $('.change-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').text(data.query.results.quote<?php if(count($stocks) != '1'){ echo "[".$subsubindex."]"; } ?>.Change + " (" + change<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>[1] + ")");
              var value<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = number_format(float_fix(current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>), 2);
              var csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> - <?=$stock['price']?>;
              var csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = (csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>) / (<?=$stock['amount']?> * <?=$stock['price']?>);
              overallgain = overallgain + (csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$stock['amount']?>);

              if(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> > 0){
                csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = "Return: <a style='color:green;'>+"+number_format(float_fix(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+" (+"+number_format(float_fix(csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+"%)</a>";
              }else{
                csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> = "Return: <a style='color:red;'>"+number_format(float_fix(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+" ("+number_format(float_fix(csppercent<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>), 2)+"%)</a>";
              }
              $('.csp-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').html(csp<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>);
              $('.current-value-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>').text(value<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>);
              portfolio = portfolio + (current<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?> * <?=$totall[$stock['stock']]?>);
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
    <!-- Le styles -->

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
      <div id="dialog" title="Error"><p id="dialogtext"></p></div>
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
      <?=$market?>
      <div class="row">
        <div class="col-md-5">
          <div style="text-align:center;white-space:nowrap;"><div class="personal"><h2>Cash<h2><h3>$<?php echo $user['value']; ?></h3></div><div class="personal"><h2>Portfolio Value</h2><h3 id="portval">Loading...</h3></div><div class="personal"><h2>Gain<small style="color:white;text-shadow:none;">*</small></h2><h3 id="gain">Loading...</h3></div></div>
          <div class="table" style="height:680px;overflow-y:auto;">
          
          <?php if(empty($stocks)){ echo "<h3 style='text-align:center;background:rgba(255,255,255,0.3);padding:15px;text-shadow:1px 1px 1px rgba(0,0,0,0.3);'>No stocks to show. <a href='/market'>Invest!</a></h3>"; } ?>
          <?php if($marketclosed){ 
              $queue = get_stock_queue($_SESSION['id']); ?>
              <?php foreach($queue AS $stock){ ?>
              <div class="stock disabled">
                <div class="row">
                  <div class="col-md-8">
                    <p><h1><a href='search.php?q=<?=$stock['symbol']?>'><?=$stock['symbol']?><span class="glyphicon glyphicon-chevron-right" style="font-size:0.5em;top:-4.5px;"></a></h1></p>
                    <p><b><?=$stock['name']?></b></p>
                  </div>
                  <div class="col-md-4">
                    <p>Quantity: <b><?=$stock['amount']?></b></p>
                    <p>Operation: <b><?=$stock['operation']?></b></p>
                    <p>Price: <b>$<?=number_format($stock['price']/$stock['amount'], 2, '.', ',')?></b>/share</p>
                  </div>
                </div>
              </div>
              <?php } ?>
              <?php } ?>
            <?php 
              foreach ($stocks as $stock) {
                $index++; ?>
              <div class="stock">
                <div class="row">
                  <div class="col-md-8">
                    <p><h1><a href='search.php?q=<?=$stock['stock']?>'><?=$stock['stock']?><span class="glyphicon glyphicon-chevron-right" style="font-size:0.5em;top:-4.5px;"></span></a></h1></p>
                    <p><b><?=$stock['name']?></b></p>
                    <?php if($stock['short'] == 1){ echo "<p><i>Short</i></p>"; } ?>
                  </div>
                  <div class="col-md-4">
                    <p>Quantity: <b><?=$stock['amount']?></b></p>
                    <p class='stat current-price-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>'>Current: Loading...</p>
                    <!-- <p id='52-high-<?=$index?>'>Loading...</p>
                    <p id='52-low-<?=$index?>'>Loading...</p>
                    <p class='stat' id='change-<?=$index?>'>Loading...</p> -->
                    <?php if($stock['short'] == 1){ ?><p>Purchase Price: <b>$<?=$stock['price']?></b></p><?php }else{ ?><p class='stat csp-<?=preg_replace("/[^a-zA-Z0-9]+/", "",$stock['stock'])?>'>Return: Loading...</p><?php } ?>
                    <!-- <p id='pe-<?=$index?>'>Loading...</p> -->
                  </div>
                </div>
              </div>
              <?php } ?>
          </div>
        </div>
        <div class="col-md-6 col-md-offset-1">
          <h1 style="text-align:center;margin:15px;margin-top:39px;padding:15px;">My Stats</h1>
          <div class="table" style="background:#eee;padding:15px;text-align:center;color:black;height:680px;">
            <h2>Funds</h2>
            <h6>Where your money is going</h6>
            <canvas id="Fundds" width="210" height="210"></canvas>
            <h2>Performance</h2>
            <h6>Your performance in the last fiscal quarter.</h6>
            <canvas id="Performancce" width="420" height="210"></canvas>
            <p><a href="/history" class="btn btn-primary btn-lng">View Previous Transactions</a></p>
          </div>
        </div>
      </div>
      <small>*Not including shorts.</small>
      <hr>

      <div class="footer">
        <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/Charts.min.js"></script>
    <?php if(!empty($stocks)){ ?>
    <script type="text/javascript">
      var data = [
        <?php 
        function getColor($orig, $max, $iteration) {
			$sat = 75;
			$dark = 60;
			
			$newval = $orig + ((360/$max)*$iteration);
			$newval = $newval % 360;

            return "hsla(" . $newval . ", ".$sat."%, ".$dark."%, ";
        }
        $index = -1; $subindex = -1; $subsubindex = -1; 
        $code = array();
        $basecolor = rand(0,360);
        foreach($stocks AS $stock){
          $index++;
          $color = getColor($basecolor, count($stocks), $index);
          $total = $stock['price'] * $stock['amount'];
          $code[] = "{ value: {$total}, color: \"$color 1)\", highlight: \"$color 0.5)\", label: \"{$stock['stock']}\"}";
        }
        echo implode(",", $code);
        ?>
      ];
      var chartdata = {
        <?php
        $json = json_decode(file_get_contents("/var/www/core/history/{$user['id']}/history"), true);
        $label = array();
        $data = array();
        if(empty($json["Daily"])){
          if(empty($label)){
            $label = array('"30/09/14"', '"01/10/14"');
            $data = array("100000", "100000");
          }
        }else{
		    $index = 0;
          foreach ($json["Daily"] as $daily) {
          	$index++;
          	if((($index)%3) == 0 && $daily["Timestamp"] > 1420088400){
            	$label[] = '"'.date('d/m/y', $daily["Timestamp"]).'"';
            	$data[] = $daily["Value"];
            }else{
	            
            }
          }
          
        }
        if(count($label) == 1 && count($label) == 1){
          array_unshift($label, '"30/09/14"');
          array_unshift($data, "100000");
        }
        ?>
          labels: [<?=implode(",",$label)?>] ,
          datasets: [
              {
                  label: "Performance",
                  fillColor: "rgba(220,220,220,0.2)",
                  strokeColor: "rgba(220,220,220,1)",
                  pointColor: "rgba(220,220,220,1)",
                  pointStrokeColor: "#fff",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [<?=implode(",",$data)?>]
              }
          ]
      };
      var chart1 = $("#Fundds").get(0).getContext("2d");
      var chart2 = $("#Performancce").get(0).getContext("2d");
      <?php // if(empty($label)){ echo "chart2.fillText(\"Hello World!\",50,50);"; } ?>
      var myDoughnutChart = new Chart(chart1).Doughnut(data, {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke : true,

          //String - The colour of each segment stroke
          segmentStrokeColor : "#fff",

          //Number - The width of each segment stroke
          segmentStrokeWidth : 2,

          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout : 50, // This is 0 for Pie charts

          //Number - Amount of animation steps
          animationSteps : 100,

          //String - Animation easing effect
          animationEasing : "easeOutBounce",

          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate : true,

          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale : false,
          tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= '$' + number_format(value) %>",

          //String - A legend template
          legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

      });
      var myNewChart = new Chart(chart2).Line(chartdata, {

            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines : true,

            //String - Colour of the grid lines
            scaleGridLineColor : "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            scaleGridLineWidth : 1,

            //Boolean - Whether the line is curved between points
            bezierCurve : true,

            //Boolean - Whether to show a dot for each point
            pointDot : true,

            //Number - Radius of each point dot in pixels
            pointDotRadius : 3,

            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth : 1,

            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 1,

            //Boolean - Whether to show a stroke for datasets
            datasetStroke : true,

            //Number - Pixel width of dataset stroke
            datasetStrokeWidth : 2,

            //Boolean - Whether to fill the dataset with a colour
            datasetFill : true,

            responsive: false,

            maintainAspectRatio: true,

            scaleLabel : "<%=number_format(value)%>"

      });
    </script>
    <?php } ?>
    
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>