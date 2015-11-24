<?php

include('core/init.inc.php');

if(isset($_SESSION['email']) === false){
	die('You need to login.');
}

// if(is_writer($_SESSION['id'])){
// 	die("Staff cannot participate in the competition.");
// }

if(isset($_GET['q'], $_GET['s'])){
	if(empty($_GET['q']) || empty($_GET['s'])){
		die("Both quantity and ticker must be filled out.");
	}
	if($_GET['q'] <= 0){
		die("You have to buy one or more shares.");
	}
	$query = $_GET['s'];
	$success = false;
	for ($i=1;$i<=10;$i++) {
        $prices = json_decode(file_get_contents("http://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20symbol%2C%20ErrorIndicationreturnedforsymbolchangedinvalid%2C%20Name%20from%20yahoo.finance.quotes%20where%20symbol%20%3D%20%22{$query}%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys"), true);
        if(!empty($prices['query']['results'])){
            $success = true;
            break;
        }
    }
    if(!$success){
		die("An error occurred. Please try again later");
	}
	if(valid_yql_query($prices) === false){
		die('Invalid stock');
	}
	if($success){
        $ticker = $prices['query']['results']['quote']["symbol"];
        $name = $prices['query']['results']['quote']['Name'];
        $prices = explode(" - ", $prices['query']['results']['quote']["LastTradeWithTime"]);
        $prices = preg_replace("/(<([^>]+)>)/i", "", $prices[1]);
        $final = array("name" => $name, "symbol" => $ticker, "price" => $prices);
    }
	if($marketclosed){
		if(after_market_buy($_SESSION['id'], $final["price"], $_GET['s'], $final['name'], $_GET['q']) === false){
			die('You do not have enough money to buy this stock.');
		}
		log_change($_SESSION['id'], $_SESSION['email'], $_GET['s'], $_GET['q'], $final["price"]);
	}else{
		if(buy_stock($_SESSION['id'], $final["price"], $_GET['s'], $final['name'], $_GET['q']) === false){
			die('You do not have enough money to buy this stock.');
		}
		log_change($_SESSION['id'], $_SESSION['email'], $_GET['s'], $_GET['q'], $final["price"], true, false);
	}
	header("Location: portfolio.php");
	die();
}else{
	die('An error occurred.');
}

?>