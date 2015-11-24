<?php

include('core/init.inc.php');

if(isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
   die();
}

if(isset($_GET['q']) === false){
	$errors[] = 'Stock not specified.';
}elseif(isset($_GET['err'])){
  $errors[] = 'Invalid stock.';
}else{
  if(basic_validation($_GET['q']) == false){
    $errors[] = 'Invalid stock.';
  }
}

$symbol = html_escape($_GET['q']);
$symbolpartial = urlencode($_GET['q']);
$symbolurl = urlencode(mysql_real_escape_string($_GET['q']));

$isshort = is_short($_SESSION['id'], $_GET['q']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $siteinfo['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteinfo['description']; ?>">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/smoothness/jquery-ui-1.10.3.custom.min.css">
    <?php if(isset($_GET['q']) && empty($errors)){ ?>
    <script type="text/javascript">function number_format(e,t,n,r){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var i=!isFinite(+e)?0:+e,s=!isFinite(+t)?0:Math.abs(t),o=typeof r==="undefined"?",":r,u=typeof n==="undefined"?".":n,a="",f=function(e,t){var n=Math.pow(10,t);return""+Math.round(e*n)/n};a=(s?f(i,s):""+Math.round(i)).split(".");if(a[0].length>3){a[0]=a[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)}if((a[1]||"").length<s){a[1]=a[1]||"";a[1]+=(new Array(s-a[1].length+1)).join("0")}return a.join(u)}function float_fix(e){var t=Math.round(e*100)/100;return t.toFixed(2)}$(function() {
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
  });$(document).ready(function() { $(".graph").attr("title",  $(".graph").attr("src") + "&dummy=" + (new Date()).getTime()); function alertr(message){ $( "#dialog" ).dialog( "open" ); $("#dialogtext").text(message); var a=document.title;setInterval(function(){document.title=document.title==a?"Error":a},1500); }
     $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20ErrorIndicationreturnedforsymbolchangedinvalid%2C%20Change%2C%20ChangeinPercent%2C%20Name%2C%20PERatio%2C%20YearHigh%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%20%3D%20%22<?=$symbolurl?>%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys")
    .done(function(data){
      if(data.query.results == "" || data.query.results.ErrorIndicationreturnedforsymbolchangedinvalid != null || data.query.results.quote.LastTradeWithTime == null || data.query.results.quote.LastTradeWithTime == "N/A - <b>0.00</b>"){
        window.location.replace("http://marketdream.org/search.php?q=<?=$symbolpartial?>&err");
      }
      $(document).ready(function() {	
        $('#company-name').text(data.query.results.quote.Name);
        var current = data.query.results.quote.LastTradeWithTime.split(' - ');
        current = current[1].replace(/(<([^>]+)>)/ig,"");
        $('#current-price').text(number_format(current, 2));
        $('#infoprice').attr("value", number_format(current, 2));
        dothemath();
        $('#change').text(data.query.results.quote.Change);
        var change = data.query.results.quote.ChangeinPercent.split(' - ');
        $('#change-percent').text(change[1]);
        $('#52-high').text(data.query.results.quote.YearHigh);
        $('#52-low').text(data.query.results.quote.YearLow);
        if(data.query.results.quote.PERatio == null){
          data.query.results.quote.PERatio = 'N/A';
        }
        $('#pe').text(data.query.results.quote.PERatio);
      });
    })
    .fail(function(err) {
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
          $('.stat').text("Updating...");
          $.getJSON("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20Change%2C%20ChangeinPercent%20%20from%20yahoo.finance.quotes%20where%20symbol%20%3D%20'<?=$symbolurl?>'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys")
              .done(function (data) {
              var current = data.query.results.quote.LastTradeWithTime.split(' - ');
			       current = current[1].replace(/(<([^>]+)>)/ig,"");
              $('#current-price').text(number_format(current, 2));
              $('#infoprice').attr("value", number_format(current, 2));
              var changepercent = data.query.results.quote.ChangeinPercent.split(' - ');
              $('#change').text(data.query.results.quote.Change);
              $('#change-percent').text(changepercent[1]);
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
        $(".graph").attr("title",  $(".graph").attr("src") + "&dummy=" + (new Date()).getTime());
      }
    });</script>
    <?php } ?>
    <!-- Le styles -->
    <style type="text/css">.statcrop>p{text-align:center}.statcrop>p>a{margin-left:10px;margin-right:10px} .tab-content{text-align: center;} .dater a{margin-left:5px;margin-right:5px;}</style>
	   <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
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
    <div class="navbar dark" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand dark" href="/"><?php echo $siteinfo['title']; ?></a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav dark navbar-nav">
              <li class="active"><a href="/home">Home</a></li>
              <li><a href="/toolbox"><?=$viewd?>Toolbox</a></li>
              <li><a href="/market">Market</a></li>
              <li><a href="/leaderboard">Leaderboard</a></li>
              <li><a href="/portfolio">Portfolio</a></li>
            </ul>
            <ul class="nav dark navbar-nav navbar-right">
              <?=$rightnav?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="body" style="color:black;background:#eee;padding:25px;padding-top:0;">
      <hr id='navbarhr'>
      <?=$market?>
      <form class="form-inline" method='get' action='search' style='text-align:center;'>
        <label for="search">Search for a Stock:</label>
        <input type="text" class="form-control search-query" name="q" id='search' autocomplete="off" placeholder="Stock Name or Symbol" />
        <button type="submit" class="btn">Search</button>
      </form>
      <?php

      if(empty($errors) === false){
      	?><ul>
      	<?php
      		foreach ($errors as $error) {
      			?><li><?php echo $error; ?></li><?php
      		}
      	?></ul><?php
      }else{
      	?><h1><?php echo strtoupper(html_escape($_GET['q'])); ?></h1>
        <div class="center" style="text-align:center">
            <canvas id="myChart" width="800" height="400"></canvas>
            <p class="dater"><a href="#" onclick="update_chart('1d', true);">1d</a><a href="#" onclick="update_chart('5d', false);">5d</a><a href="#" onclick="update_chart('1m', false);">1m</a><a href="#" onclick="update_chart('6m', false);">6m</a><a href="#" onclick="update_chart('1y', false);">1y</a><a href="#" onclick="update_chart('5y', false);">5y</a></p>
        </div>
            <table class="table table-striped table-bordered"><tbody>
                <tr>
                  <td><b>Company Name</b></td>
                  <td id='company-name'>Loading...</td>
                </tr>
                <tr>
                  <td><b>Current Price</b></td>
                  <td id='current-price' class='stat'>Loading...</td>
                </tr>
                <tr>
                  <td><b>Change</b></td>
                  <td id='change' class='stat'>Loading...</td>
                </tr>
                <tr>
                  <td><b>Change (Percent)</b></td>
                  <td id='change-percent' class='stat'>Loading...</td>
                </tr>
                <tr>
                  <td><b>52 Week High</b></td>
                  <td id='52-high'>Loading...</td>
                </tr>
                <tr>
                  <td><b>52 Week Low</b></td>
                  <td id='52-low'>Loading...</td>
                </tr>
                <tr>
                  <td><b>PE ratio</b></td>
                  <td id='pe'>Loading...</td>
                </tr>
              </tbody>
            </table><?php
        if(isset($_SESSION['email'])){
          if(if_owned($_SESSION['id'], $_GET['q'])){
            $owned = 1;
            $stockamount = stock_amount($_SESSION['id'], $_GET['q']);
          }
          if(short_owned($_SESSION['id'], $_GET['q'])){
            $shortowned = 1;
            $shortamount = short_amount($_SESSION['id'], $_GET['q']);
          }
          ?><p><?php if(isset($shortowned)){ ?><a class='btn btn-lg btn-warning' onclick="frombuy();" style='margin-right:10px;' role="button" data-toggle="modal" href="#buy">Short</a><?php }else{ ?><a class='btn btn-lg btn-primary' style='margin-right:10px;' role="button" data-toggle="modal" href="#buy">Buy</a><?php } if(isset($shortowned)){ ?><a class='btn btn-lg btn-danger' role="button" style='margin-right:10px;' data-toggle="modal" href="/coverme?q=<?=$symbol?>">Cover</a><?php } if(isset($owned)){ ?><a class='btn btn-lg btn-success' role="button" data-toggle="modal" href="#sell">Sell</a><?php } ?></p>
          <div id="buy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="buylabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 id="buylabel">Buying Stock</h3>
                </div>
                <div class="modal-body">
                  <p><form action='/buy' style='text-align:center;' id='buyform' method='get' class="form-inline"><label for="quantity">Quantity: </label> <input name="q" id='quantity' onkeyup="dothemath();" type='text' value='1' class='form-control' style="width:75px" autofocus> &times; <label for="infoprice">Price: </label> <input id='infoprice' type='text' class='form-control' style="width:100px" disabled> + <label for="infofee">Fee: </label> <input id="infofee" type='text' value='$10.00' class='form-control' style="width:50px" disabled><h3>Total: <b id="matht"></b></h3><input type='hidden' name='s' value='<?=$symbol?>' /></form></p>
                </div>
                <div class="modal-footer">
                <div class="btn-group">
                  <button type="submit" class="btn btn-primary" form='buyform' id="buybutton">Buy</button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" id="buybuttontg">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#" id="buyotheroption" onclick="frombuy();">Short</a></li>
                  </ul>
                </div>
                  <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          <?php if(isset($owned) || isset($shortowned)){ ?>
          <div id="sell" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="sellabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h3 id="sellabel">Selling Stock</h3>
                </div>
                <div class="modal-body">
                  <p style='text-align:center;'><b>You own <?=$stockamount?> shares.</b><form action='sell.php' style='text-align:center;' id='sellform' method='get' class="form-inline"><label for="quantity">Quantity: </label><input name="q" id='quantity' type='text' value='1' class='form-control' autofocus><input type='hidden' name='s' value='<?=$symbol?>' /></form></p>
                </div>
                <div class="modal-footer">
                <?php if(isset($shortowned) && isset($owned)){ ?>
                <div class="btn-group">
                  <button type="submit" class="btn btn-primary" form='sellform' id="sellbutton">Sell</button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" id="sellbuttontg">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#" id="sellotheroption" onclick="fromshort();">Cover</a></li>
                  </ul>
                </div>
                <?php }elseif(isset($shortowned) && !isset($owned)){ ?>
                <button type="submit" class="btn btn-primary" form='coverform' id="sellbutton">Cover</button>
                <?php }elseif(isset($owned) && !isset($shortowned)){ ?>
                <button type="submit" class="btn btn-primary" form='sellform' id="sellbutton">Sell</button>
                <?php } ?>
                  <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
      }

      ?>
      <hr>
      <?php
      $feed = simplexml_load_file("http://feeds.finance.yahoo.com/rss/2.0/headline?s={$_GET['q']}&region=US&lang=en-US");
      $item = $feed->channel->item;

      $index = -1;
      foreach ($feed->channel->item as $value) {
        $index++;
        if($value->description == '' || strpos($value->description, '[Breakout]') !== false|| strpos($value->description, '[Talking Numbers]') !== false){
          $value->description = '<i>A preview is not available for this post.</i>';
        }
        $feed->channel->item[$index]->description = substr(strip_tags($value->description, '<i><b>'), 0, 250);
        $feed->channel->item[$index]->title = strip_tags($value->title);
      }
      ?>
      <h3 style='text-align:center;'>News</h4>
      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-5">
        <?php if(isset($item[0])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[0]->link?>'><?=$item[0]->title?></a></h4>
          <p><?=$item[0]->description?>... <a target="_blank" href='<?=$item[0]->link?>'>Read More</a></p>
          <?php } ?>
		  <?php if(isset($item[2])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[2]->link?>'><?=$item[2]->title?></a></h4>
          <p><?=$item[2]->description?>... <a target="_blank" href='<?=$item[2]->link?>'>Read More</a></p>
          <?php } ?>

		  <?php if(isset($item[4])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[4]->link?>'><?=$item[4]->title?></a></h4>
          <p><?=$item[4]->description?>... <a target="_blank" href='<?=$item[4]->link?>'>Read More</a></p>
          <?php } ?>
          <small>News, Charts, and Stats brought to you by <a target="_blank" href='http://finance.yahoo.com/'>Yahoo Finance</a></small>
        </div>
        
        <div class="col-md-5">
        <?php if(isset($item[1])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[1]->link?>'><?=$item[1]->title?></a></h4>
          <p><?=$item[1]->description?>... <a target="_blank" href='<?=$item[1]->link?>'>Read More</a></p>
          <?php } ?>
		  <?php if(isset($item[3])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[3]->link?>'><?=$item[3]->title?></a></h4>
          <p><?=$item[3]->description?>... <a target="_blank" href='<?=$item[3]->link?>'>Read More</a></p>
          <?php } ?>

		  <?php if(isset($item[5])) { ?>
          <h4><a style="color:black;border-bottom: 1px grey dotted;" id='newslink' target="_blank" href='<?=$item[5]->link?>'><?=$item[5]->title?></a></h4>
          <p><?=$item[5]->description?>... <a target="_blank" href='<?=$item[5]->link?>'>Read More</a></p>
          <?php } ?>
        </div>

      </div>

      <?php } ?>
      <hr>

      <div class="footer">
          <hr>
          <p>&copy; <?php echo $siteinfo['title']; ?> 2014&emsp;<a href="privacy.php" id='privacy'>Privacy Policy</a>&emsp;<a href="report.php" id='privacy'>Report a Bug</a></p>
        </div>
      </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="assets/js/Charts.min.js"></script>
    <script src="//marketdream.org/assets/js/prefixfree.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">$(document).ready(function() {$("#search").autocomplete({source:function(c,d){(window.YAHOO={Finance:{SymbolSuggest:{}}}).Finance.SymbolSuggest.ssCallback=function(a){a=$.map(a.ResultSet.Result,function(b,a){if("S"==b.type||"C"==b.type)return{label:b.symbol+" ("+b.name+")",value:b.symbol}});d(a)};$.getScript(["http://d.yimg.com/autoc.finance.yahoo.com/autoc?","query="+c.term,"&callback=YAHOO.Finance.SymbolSuggest.ssCallback"].join(""))},minLength:2}); })</script>
    <script type="text/javascript">if (window.location.href.match(/\#buy/)){ $('#buy').modal('show'); }</script>
    <script type="text/javascript">if (window.location.href.match(/\#sell/)){ $('#sell').modal('show'); }</script>
    <?php if(isset($_COOKIE['alert_close'])){ ?><script type="text/javascript">$(".alert").alert('close')</script><?php } ?>
    <script type="text/javascript">
    function update_chart(span, istime){
      $("#myChart").replaceWith("<canvas id=\"myChart\" width=\"800\" height=\"400\"></canvas>");
      var timerr = [];
      var dataa = [];
       $.ajax({

        url: "http://chartapi.finance.yahoo.com/instrument/1.0/<?=$symbolurl?>/chartdata;type=quote;range="+span+"/json",
        jsonp: true,
        jsonpCallback: "finance_charts_json_callback",
        cache: false,
        dataType: 'jsonp'
      })
      .done(function(data){
        var reducer = 0;
        var further = 0;
        var oncemore = 0;
        console.log(data.series.length);
        for (var i = 0; i < data.series.length; i++) {
          v = i + 1;
          var date = new Date(data.series[i]["Timestamp"] * 1000);
          if(v == Math.round(data.series.length * 0.2) || v == Math.round(data.series.length * 0.4) || v == Math.round(data.series.length * 0.6) || v == Math.round(data.series.length * 0.8) || v == Math.round(data.series.length * 1)){
            var time;
            if(istime){
              if(date.getHours() > 12){
                time = (date.getHours() - 12) + " PM";
              }else{
                time = date.getHours() + " AM";
              }
            }else{
              if(span != "1d" && span != "5d"){
                var dating = data.series[i]["Date"];
                console.log(dating);
                time = dating.toString().substring(0,4) + "/" + dating.toString().substring(4,6) + "/" + dating.toString().substring(6,8);
              }else{
                time = date.toLocaleDateString();
              }
            }
            timerr.push(time);
            dataa.push(data.series[i]["close"]);
          }else{
            if(data.series.length < 100){
              console.log("Less");
              timerr.push("");
              dataa.push(data.series[i]["close"]);
            }else{
              console.log("More");
              if(data.series.length > 200){
                console.log("Redux 2");
                console.log(reducer);
                if(reducer == 4){
                  timerr.push("");
                  dataa.push(data.series[i]["close"]);
                  reducer = 0;
                }else{
                  reducer++;
                }
              }else{
                console.log("Redux 1");
                if(reducer == 1){
                  timerr.push("");
                  dataa.push(data.series[i]["close"]);
                  reducer = 0;
                }else{
                  reducer++; 
                }
              }
            }
          }
        };
        console.log(timerr);
        console.log(dataa);
        var chartdata = {
            labels: timerr,
            datasets: [
                {
                    label: "Dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: dataa
                }
            ]
        };
        var ctx = $("#myChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var myNewChart = new Chart(ctx).Line(chartdata, {

            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines : true,

            //String - Colour of the grid lines
            scaleGridLineColor : "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            scaleGridLineWidth : 1,

            //Boolean - Whether the line is curved between points
            bezierCurve : false,

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

            responsive: true,

            maintainAspectRatio: false

        });
      })
      .fail(function(err) {
        if(typeof failed == 'undefined'){
          if(err.status == "500"){
            alertr("Yahoo is having issue. Please reload and try back later.");
          }else{
            alertr("Request Failed. Please reload the page.");
              
          }
          failed = 'failed';
        }
    });
    }
    update_chart("1d", true);
    </script>
    <script type="text/javascript">
        function frombuy() {
          // Change the buy button
          $('#buybutton').removeClass('btn-primary');
          $('#buybutton').addClass('btn-warning');
          $('#buybutton').text("Short");
          $('#buylabel').text("Shorting Stock");

          // Change the buy toggle
          $('#buybuttontg').removeClass('btn-primary');
          $('#buybuttontg').addClass('btn-warning');

          // Modify the buy form
          $('#buyform').attr("action", "/short");

          // Modify self
          $('#buyotheroption').attr("onclick", "fromshort();");
          $('#buyotheroption').text("Buy");
        }

        function fromsell() {
          // Change the buy button
          $('#sellbutton').removeClass('btn-success');
          $('#sellbutton').addClass('btn-danger');
          $('#sellbutton').text("Cover");

          $('#selllabel').text("Covering Stock");

          // Change the buy toggle
          $('#sellbuttontg').removeClass('btn-success');
          $('#sellbuttontg').addClass('btn-danger');

          // Modify the buy form
          $('#sellform').attr("action", "/cover");

          // Modify self
          $('#sellotheroption').attr("onclick", "fromcover();");
          $('#sellotheroption').text("Sell");
        }

        function fromshort() {
          // Change the buy button
          $('#buybutton').removeClass('btn-warning');
          $('#buybutton').addClass('btn-primary');
          $('#buybutton').text("Buy");

          // Change Header
          $('#buylabel').text("Buying Stock");

          // Change the buy toggle
          $('#buybuttontg').removeClass('btn-warning');
          $('#buybuttontg').addClass('btn-primary');

          // Modify the buy form
          $('#buyform').attr("action", "/buy");

          // Modify self
          $('#buyotheroption').attr("onclick", "frombuy();");
          $('#buyotheroption').text("Short");
        }

        function fromcover() {
          // Change the buy button
          $('#sellbutton').removeClass('btn-danger');
          $('#sellbutton').addClass('btn-success');
          $('#sellbutton').text("");

          // Change Header
          $('#selllabel').text("Selling Stock");

          // Change the buy toggle
          $('#sellbuttontg').removeClass('btn-danger');
          $('#sellbuttontg').addClass('btn-success');

          // Modify the buy form
          $('#sellform').attr("action", "/sell");

          // Modify self
          $('#sellotheroption').attr("onclick", "fromsell();");
          $('#sellotheroption').text("Cover");
        }

        function dothemath() {
          var math = parseFloat(10 + (parseFloat($('#infoprice').val()) * parseInt($('#quantity').val()) ));
          if(math > <?php echo get_user_cash($_SESSION['id']); ?>){
            $('#matht').css({"color": "red"});
          }else{
            $('#matht').css({"color": "black"});
          }
          $('#matht').text("$"+ number_format(math, 2));
        }
    </script>
  </body>
</html>
