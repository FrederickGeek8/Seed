<?php

include("../core/init.inc.php");
include("init.inc.php");

$errors = array();
if(isset($_POST['ratio1'], $_POST['ratio2'], $_POST['symbol'], $_POST['exday'], $_POST['exmonth'], $_POST['exyear'], $_POST['payday'], $_POST['paymonth'], $_POST['payyear'])){
	foreach($_POST AS $element){
		if(empty($element)){
			$errors = array('All elements must be filled out.');
		}
	}
	if($_POST['exday'] == 'DD' || $_POST['payday'] == 'DD' || $_POST['exmonth'] == 'MM' || $_POST['paymonth'] == 'MM' || $_POST['exyear'] == 'YY' || $_POST['payyear'] == 'YY'){
		$errors = array('All elements must be filled out.');
	}
	$exday = (int)$_POST['exday'];
	$exmonth = (int)$_POST['exmonth'];
	$exyear = (int)$_POST['exyear'];
	$payday = (int)$_POST['payday'];
	$paymonth = (int)$_POST['paymonth'];
	$payyear = (int)$_POST['payyear'];
	$exdate = strtotime("{$exday}-{$exmonth}-20{$exyear}");
	$paydate = strtotime("{$payday}-{$paymonth}-20{$payyear}");
	$ratio = (int)$_POST['ratio1'].":".(int)$_POST['ratio2'];
	if(empty($errors)){
		add_split($ratio, $_POST['symbol'], $exdate, $paydate);
		header("Location: splits.php");
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
		text-decoinputRation: none;
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
		<div id="split" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="splitLabel"
aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="splitLabel">Adding New Stock Split</h3>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="splitform" method="post" action="#">
                    <div class="control-group">
                        <label label-default="label-default" class="control-label label-default"
                        for="inputEmail">Symbol:</label>
                        <div class="controls">
                            <input type="text" onkeyup="this.value=this.value.toUpperCase();" id="inputEmail"
                            class="input-sm" name="symbol" placeholder="Symbol" required="required"
                            />
                        </div>
                    </div>
                    <div class="control-group">
                        <label label-default="label-default" class="control-label label-default"
                        for="inputRatio">Ratio<small>*</small>:</label>
                        <div class="controls">
                            <input type="number" id="inputRatio" min="2" name="ratio1" max="999" maxlength="3"
                            class="input-mini" placeholder="2" required="required" />:
                            <input type="number" name="ratio2" id="inputRatio" min="1" max="999"
                            maxlength="3" class="input-mini" placeholder="1" required="required" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label label-default="label-default" class="control-label label-default"
                        for="inputExDate">Ex-Date<small>**</small>:</label>
                        <div class="controls">
                            <select class="input-mini" id="inputExDate" name="exday" required="required">
                                <option>DD</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                                <option>08</option>
                                <option>09</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                                <option>16</option>
                                <option>17</option>
                                <option>18</option>
                                <option>19</option>
                                <option>20</option>
                                <option>21</option>
                                <option>22</option>
                                <option>23</option>
                                <option>24</option>
                                <option>25</option>
                                <option>26</option>
                                <option>27</option>
                                <option>28</option>
                                <option>29</option>
                                <option>30</option>
                                <option>31</option>
                            </select>/
                            <select class="input-mini" name="exmonth" required="required">
                                <option>MM</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                                <option>08</option>
                                <option>09</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                            </select>/
                            <select class="input-mini" name="exyear" required="required">
                                <option>YY</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label label-default="label-default" class="control-label label-default"
                        for="inputPayable">Payable<small>***</small>:</label>
                        <div class="controls">
                            <select class="input-mini" id="inputPayable" name="payday" required="required">
                                <option>DD</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                                <option>08</option>
                                <option>09</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                                <option>16</option>
                                <option>17</option>
                                <option>18</option>
                                <option>19</option>
                                <option>20</option>
                                <option>21</option>
                                <option>22</option>
                                <option>23</option>
                                <option>24</option>
                                <option>25</option>
                                <option>26</option>
                                <option>27</option>
                                <option>28</option>
                                <option>29</option>
                                <option>30</option>
                                <option>31</option>
                            </select>/
                            <select class="input-mini" name="paymonth" required="required">
                                <option>MM</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                                <option>08</option>
                                <option>09</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                            </select>/
                            <select class="input-mini" name="payyear" required="required">
                                <option>YY</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                            </select>
                        </div>
                    </div>
                </form>	<small style="font-size: 0.75em;">*The first value is the resulting stock # and the second value is the beginning amount. **Ex-Date is the date that the stock is scheduled to split. ***Payable date is the date when the stocks traded are not affected by the split.</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary" type="submit" form="splitform">Add Split</button>
            </div>
        </div>
    </div>
</div>

	<p>
	<?php
	
	if (empty($errors) === false){
	  ?>
	  <ul>
	    <?php
	
	    foreach ($errors as $error){
	      echo "<li>{$error}</li>";
	    }
	
	    ?>
	  </ul>
	  <?php
	}
	
	?>
	</p>
	  <div class="well"><h1>Split Calendar <small><a href="#split" style="margin-top:-1em;" role="button" class="btn btn-success" data-toggle="modal">New Split</a></small></h1><table class="table table-striped"><thead><tr><th>Symbol</th><th>Ratio</th><th>Ex-Date</th><th>Payable</th></tr></thead><tbody><?php $splits = get_splits(); foreach($splits AS $split){ ?><tr><td><?=$split['symbol']?></td><td><?=$split['ratio']?></td><td><?=$split['exdate']?></td><td><?=$split['paydate']?></td></tr><?php } ?></tbody></table></div><p><small><a target="_blank" href="http://www.briefing.com/investor/calendars/stock-splits/">http://www.briefing.com/investor/calendars/stock-splits/</a></small></p>

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
	<script type="text/javascript">if (window.location.hash == "#split"){ $("#split").modal('show'); }</script>

  </body>
</html>