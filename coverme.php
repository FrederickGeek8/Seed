<?php

// die("Covers are temporarily closed. Will be back in a few hours.");

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

if(short_owned($_SESSION['id'], $_GET['q'])){
  $shortowned = 1;
  $shortamount = short_amount($_SESSION['id'], $_GET['q']);
  $subshorts = get_subshorts($_SESSION['id'], $_GET['q']);
}else{
  die("You must short a stock before you cover.");
}

if(isset($_GET['q'], $_GET['t'])){
  $valdiation = valid_short_transaction($_SESSION['id'], $_GET['q'], $_GET['t']);

if($valdiation) {
  $help = get_subshort($_SESSION['id'], $_GET['t']);
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
        $('.infoprice').attr("value", number_format(current, 2));
        dothemath2();
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
              $('.infoprice').attr("value", number_format(current, 2));
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
      <br/>
      <h1 style="text-align:center">Covering Stock</h1>
      <?php
        if(isset($_GET['t'])){
          if($valdiation){
            
            ?><p><form action='/cover' style='text-align:center;' id='sellform' method='get' class="form-inline"><p><select id="helpme" name="helpme" class="form-control" style="width:150px;" disabled><option value="<?=$help['i_id']?>"><?=$help['amount']?> &times; $<?=$help['price']?></option></select><p><a href="/coverme?q=<?=$symbol?>">< Back</a></p><label for="quantity2">Quantity: </label> <input name="q" id='quantity2' onkeyup="dothemath2();" type='text' value='1' class='form-control' style="width:75px" autofocus> &times; <label for="infoprice">Price: </label> <input id='infoprice' type='text' class='form-control infoprice' style="width:100px" disabled> - <label for="infofee2">Fee: </label> <input id="infofee2" type='text' value='$10.00' class='form-control' style="width:50px" disabled><h3>Total: <b id="matht2"></b></h3><input type='hidden' name='s' value='<?=$symbol?>' /><input type="hidden" name="t" value="<?=$help['i_id']?>" /><button type="submit" class="btn btn-primary">Submit</button></form></p><?php
          }else{
            echo "Invalid Transcation ID";
          }
        }else{
          ?>
          <p><form action='#' style='text-align:center;' id='sellform' method='get' class="form-inline"><p><select id="t" name="t" class="form-control" style="width:150px;"><?php $index = -1; foreach($subshorts AS $short){ $index++; echo "<option value=\"{$short['i_id']}\">{$short['amount']} &times; \${$short['price']}</option>"; } ?></select> <input type='hidden' name='q' value='<?=$symbol?>' /><button type="submit" class="btn btn-primary">Submit</button></form></p>
          <?php
        }
      ?>
      
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
    <?php if(isset($_GET['t'])){
          if(isset($valdiation)){ ?>
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
          var math = parseFloat(10 + (parseFloat($('.infoprice').val()) * parseInt($('#quantity').val()) ));
          if(math > <?php echo get_user_cash($_SESSION['id']); ?>){
            $('#matht').css({"color": "red"});
          }else{
            $('#matht').css({"color": "black"});
          }
          $('#matht').text("$"+ number_format(math, 2));
        }
        function dothemath2() {
          var index = $("#helpme").val();
          var math = (parseFloat(2* <?=$help["price"]?>)) - parseFloat($('.infoprice').val());
          math = (math * $('#quantity2').val())-10;
          if(parseInt($('#quantity2').val()) > parseInt($("#maxam2-" + index).val())){
            $('#matht2').css({"color": "red"});
          }else{
            $('#matht2').css({"color": "black"});
          }
          $('#matht2').text("$"+ number_format(math, 2));
        }
    </script>
    <?php } } ?>
  </body>
</html>
