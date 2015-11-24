<?php
header('Content-Type: application/json');
session_start();
mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');
$status = array('status' => 'failed');

include("../core/inc/holidays.inc.php");
if((date("Hi", time()) > 1300) && (1385701200 < time()) && (time() < 1385787600)){
	$marketclosed = TRUE;
	$market = "<div class='alert'>
  <a href='?close' class='close'>&times;</a>
  <strong>Heads up!</strong> The market is not open right now. All transactions will go through after it reopens.
</div>";
}

if(isset($_SESSION['email']) === false){
	$status['status'] = 'err_not_logged';
}else{
	if(isset($_REQUEST['q'], $_REQUEST['s']) && !empty($_REQUEST['q']) && !empty($_REQUEST['s'])){
		include("../core/inc/staff.inc.php");
		include("../core/inc/search.inc.php");
		
		if(is_writer($_SESSION['id'])){
			$status['status'] = "err_staff";
			echo json_encode($status);
			die();
		}
		include("../core/inc/stocks.inc.php");
		$id = (int)$_SESSION['id'];
		
		if($_REQUEST['q'] <= 0){
			$status['status'] = "err_zero";
			echo json_encode($status);
			die();
		}
		$query = $_REQUEST['s'];
		$success = false;
		for ($i=1;$i<=10;$i++) {
	        $prices = json_decode(file_get_contents("http://query.yahooapis.com/v1/public/yql?q=select%20LastTradeRealtimeWithTime%2C%20symbol%2C%20ErrorIndicationreturnedforsymbolchangedinvalid%2C%20Name%20from%20yahoo.finance.quotes%20where%20symbol%20%3D%20%22{$query}%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys"), true);
	        if(!empty($prices['query']['results'])){
	            $success = true;
	            break;
	        }
	    }
	    if(!$success){
			$status['status'] = "err_general";
			echo json_encode($status);
			die();
		}
		if(valid_yql_query($prices) === false){
			$status['status'] = "err_invalid_stock";
			echo json_encode($status);
			die();
		}
		if($success){
	        $ticker = $prices['query']['results']['quote']["symbol"];
	        $name = $prices['query']['results']['quote']['Name'];
	        $prices = explode(" - ", $prices['query']['results']['quote']["LastTradeRealtimeWithTime"]);
	        $prices = preg_replace("/(<([^>]+)>)/i", "", $prices[1]);
	        $final = array("name" => $name, "symbol" => $ticker, "price" => $prices);
	    }
		if($marketclosed){
		
		if(after_market_sell($_SESSION['id'], $final["price"], $_REQUEST['s'], $final["name"], $_REQUEST['q']) === false){
				$status['status'] = "err_stock";
				echo json_encode($status);
				die();
			}
		log_change($_SESSION['id'], $_SESSION['email'], $_REQUEST['s'], $_REQUEST['q'], $final["price"], false);
			
		}else{
		
		if(sell_stock($_SESSION['id'], $final["price"], $_REQUEST['s'], $_REQUEST['q']) === false){
				$status['status'] = "err_stock";
				echo json_encode($status);
				die();
			}
		log_change($_SESSION['id'], $_SESSION['email'], $_REQUEST['s'], $_REQUEST['q'], $final["price"], false, false);
		}
		$status['status'] = "success";
	}else{
		$status['status'] = "err_parameters";
		echo json_encode($status);
		die();
	}
}
echo json_encode($status);
die();
?>