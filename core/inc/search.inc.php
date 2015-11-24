<?php

// function to check if the query is valid
function valid_query($array){
	global $blacklist;
	if (in_array($array[0], $blacklist) || substr(urlencode($array[0]), 0, 3) === '%5E') {
		return false;
	}

	// will be invalid if index 3 - 10 are set to N/A
	// also invalid if the first index is set to "Missing Symbols List."
	if($array[2] == "N/A"){
		return false;
	}elseif($array[0] === 'Missing Symbols List.'){
		return false;
	}else{
		return true;
	}
}

function valid_yql_query($array){
	global $blacklist;
	if($array['query']['results'] == null || $array['query']['results']['quote']['ErrorIndicationreturnedforsymbolchangedinvalid'] != null || $array['query']['results']['quote']['LastTradeWithTime'] == null || $array['query']['results']['quote']['LastTradeWithTime'] == "N/A - <b>0.00</b>"){
		return false;
	}
	if (in_array($array['query']['results']['quote']['symbol'], $blacklist) || substr(urlencode($array['query']['results']['quote']['symbol']), 0, 3) === '%5E') {
		return false;
	}
}

// checks if the stock symbol is in the blacklist
function basic_validation($symbol){
	global $blacklist;
	if(in_array($symbol, $blacklist)){
		return false;
	}else{
		return true;
	}
}

?>