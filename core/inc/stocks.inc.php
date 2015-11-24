<?php

// gets popular stocks
function get_popular_stocks(){
    $sql = "SELECT
                `stock_symbol` AS `symbol`,
                `stock_name` AS `name`
            FROM `user_stocks`
            GROUP BY `stock_symbol`";
    $sql = mysql_query($sql);
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'symbol'    => $row['symbol'],
            'name'      => $row['name']
        );
    }

    $popular = array();
    foreach ($rows as $row) {
        $count = mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `stock_symbol` = '{$row['symbol']}'"), 0);
        $popular[$row['symbol']]['count'] = $count;
        $popular[$row['symbol']]['name'] = $row['name'];
    }

    function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : 1;
    }

    uasort($popular, 'cmp');
    return array_splice($popular, 0, 10);
}

function update_leaderboard($daybreak = false){
    // we want to update everyone's value according to their stocks drop/rise
    $sql = "SELECT 
                `stock_symbol` AS `symbol`
           FROM `user_stocks`
           GROUP BY `stock_symbol`";
            
    $stocks = mysql_query($sql);
    
    $rows = array();
    while (($row = mysql_fetch_assoc($stocks)) !== false){
        $rows[] = array(
            'symbol'     => $row['symbol']
        );
    }

    $stockstring = "%27";
    foreach ($rows as $stock) {
        $stockstring .= $stock["symbol"]."%27%2C%27";
    }
    $stockstring .= "%27";
    $success = false;
    
    // get the damned stocks
    for ($i=1;$i<=10;$i++) {
        $prices = json_decode(file_get_contents("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20symbol%20from%20yahoo.finance.quotes%20where%20symbol%20in%20({$stockstring})&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys"), true);
        if(!empty($prices['query']['results']['quote']) && $prices['query']['results']['quote'] != ""){
            $success = true;
            break;
        }
    }
    
    if($success){
        $final = array();
        $i = 0;
        foreach ($prices['query']['results']['quote'] as $price) {
        $i++;
            $name = $price["symbol"];
            $price = explode(" - ", $price["LastTradeWithTime"]);
            $price = preg_replace("/(<([^>]+)>)/i", "", $price[1]);
            /* ignore this for a bit
            if($price == 0.00){
                echo "shitits";
                die();
            }
            */
            $final[] = array("symbol" => $name, "price" => $price);
            mysql_query("UPDATE `users` SET `portfolio_value` = '0.00'");
        }
    }


    foreach ($final as $row) {
        // now that we have found all the required stocks, we need to gather/spread information on them
        $sql = "SELECT 
                    `user_id` AS `id`,
                    `stock_price` AS `price`,
                    `amount`,
                    `short`,
                    `i_id`
               FROM `user_stocks`
               WHERE `stock_symbol` = '{$row['symbol']}'";
        
        $stocks = mysql_query($sql);
        
        $users = array();
        while (($user = mysql_fetch_assoc($stocks)) !== false){
            $users[] = array(
                'id'     => $user['id'],
                'price'  => $user['price'],
                'amount' => $user['amount'],
                'short' => $user['short'],
                'i_id'  => $user['i_id']
            );
        }

        foreach ($users as $user) {
        	$sql = "SELECT
        			COUNT(`i_id`) AS `counter`,
                    `amount`
               FROM `stock_queue`
               WHERE `stock_symbol` = '{$row['symbol']}' AND `i_id` = '{$user['i_id']}'";
        
	        $queuecheck = mysql_query($sql);
	        $queuecheck = mysql_fetch_assoc($queuecheck);
	        
	        if($queuecheck["counter"] == "1"){
		        $user["amount"] = $user["amount"] - $queuecheck["amount"];
	        }
            if($user['short'] == 1){
                $profit = (2 * $user["price"]) - $row["price"];
                $price = $user['amount'] * $profit;
                mysql_query("UPDATE `users` SET `portfolio_value` = `portfolio_value` + '{$price}' WHERE `user_id` = '{$user['id']}'");
            }else{
                $price = $user['amount'] * $row['price'];
                mysql_query("UPDATE `users` SET `portfolio_value` = `portfolio_value` + '{$price}' WHERE `user_id` = '{$user['id']}'");
            }
        }
    }
    if($success){
        mysql_query("UPDATE `users` SET `portfolio_value` = `portfolio_value` + `user_value`");
        if($daybreak){
            $sql = "SELECT 
                    `user_id` AS `id`,
                    `portfolio_value` AS `value`
               FROM `users`";
        
        $firststage = mysql_query($sql);
        
        $secondstage = array();
        while (($user = mysql_fetch_assoc($firststage)) !== false){
            $secondstage[] = array(
                'id'     => $user['id'],
                'value' => $user['value']
            );
        }
            foreach ($secondstage as $user) {
                if(is_dir("/var/www/core/history/".$user['id']) === false){
                    mkdir("/var/www/core/history/".$user['id']);
                }
                if(file_exists("/var/www/core/history/".$user['id']."/history") === false){
                    touch("/var/www/core/history/".$user['id']."/history");
                }
                $total = mysql_query("SELECT `portfolio_value` FROM `users` WHERE `user_id` = '{$user['id']}'");
                $total = mysql_result($total, 0);
                $json = json_decode(file_get_contents("/var/www/core/history/{$user['id']}/history"), true);
                $json["Daily"][] = array("Timestamp" => time(), "Value" => $total);
                file_put_contents("/var/www/core/history/{$user['id']}/history", json_encode($json));
            }
        }
    }
}

function get_splits(){
    $sql = mysql_query("SELECT `split_symbol` AS `symbol`, `split_ratio` AS `ratio`, FROM_UNIXTIME(`split_exdate`, '%d-%m-%Y') AS `exdate`, FROM_UNIXTIME(`split_paydate`, '%d-%m-%Y') AS `paydate` FROM `splits` ORDER BY `split_exdate`");
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'symbol'    => $row['symbol'],
            'ratio'      => $row['ratio'],
            'exdate'    => $row['exdate'],
            'paydate'   => $row['paydate']
        );
    }
    
    return $rows;
}

function get_user_stocks($id, $glomp = false){
    $id = (int)$id;
    if($glomp){
        $sql = "SELECT * FROM(SELECT 
                `stock_symbol` AS `stock`,
                `amount` AS `amount`,
                `stock_name` AS `name`,
                `stock_price` AS `price`,
                `short`
           FROM `user_stocks`
           WHERE `user_id` = '{$id}'
            ORDER BY `short`, `stock_symbol` ASC ) AS `test`
           GROUP BY `stock`
           ";
    }else{
        $sql = "SELECT 
                `stock_symbol` AS `stock`,
                `amount` AS `amount`,
                `stock_name` AS `name`,
                `stock_price` AS `price`,
                `short`
           FROM `user_stocks`
           WHERE `user_id` = '{$id}'
           ORDER BY `short`, `stock_symbol` ASC";
    }
            
    $users = mysql_query($sql);
    
    $rows = array();
    while (($row = mysql_fetch_assoc($users)) !== false){
        $rows[] = array(
            'stock'     => strtoupper($row['stock']),
            'amount'    => $row['amount'],
            'name'      => $row['name'],
            'price'     => $row['price'],
            'short'     => $row['short']
        );
    }
    
    return $rows;
}

function get_stock($symbol, $extras='snl1d1t1ohgdrc1'){
    $symbol = urlencode($symbol);
    return str_getcsv(file_get_contents("http://finance.yahoo.com/d/quotes.csv?s={$symbol}&f={$extras}"));
}

function if_owned($id, $symbol){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '0'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function short_owned($id, $symbol){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1'");
    return (mysql_result($total, 0) >= '1') ? true : false;
}

function get_subshorts($id, $symbol){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $sql = "SELECT 
                `i_id`,
                `stock_symbol` AS `stock`,
                `amount` AS `amount`,
                `stock_name` AS `name`,
                `stock_price` AS `price`,
                `short`
           FROM `user_stocks`
           WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1'";
            
    $users = mysql_query($sql);
    
    $rows = array();
    while (($row = mysql_fetch_assoc($users)) !== false){
        $rows[] = array(
            'i_id'      => $row['i_id'],
            'stock'     => strtoupper($row['stock']),
            'amount'    => $row['amount'],
            'name'      => $row['name'],
            'price'     => $row['price'],
            'short'     => $row['short']
        );
    }
    
    return $rows;
}

function get_subshort($id, $tid){
    $id = (int)$id;
    $tid = (int)$tid;

    $sql = mysql_query("SELECT 
                `i_id`,
                `stock_symbol` AS `stock`,
                `amount` AS `amount`,
                `stock_name` AS `name`,
                `stock_price` AS `price`,
                `short`
           FROM `user_stocks`
           WHERE `i_id` = '{$tid}' AND `user_id` = '{$id}'");
    return mysql_fetch_assoc($sql);
}

function valid_short_transaction($id, $symbol, $tid){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));
    $tid = (int)$tid;

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `user_id` = '{$id}' AND `i_id` = '{$tid}' AND `stock_symbol` = '{$symbol}' AND `short` = '1'");
    return (mysql_result($total, 0) == '1') ? true : false;
}

function short_amount($id, $symbol){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));
    
    $total = mysql_query("SELECT `amount` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1'");
    return mysql_result($total, 0);
}

function stock_amount($id, $symbol){
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));
    
    $total = mysql_query("SELECT `amount` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '0'");
    return mysql_result($total, 0);
}

function get_stock_queue($id){
    $id = (int)$id;

    $sql = mysql_query("SELECT `stock_symbol` AS `symbol`, `amount`, `stock_name` AS `name`, `stock_price` AS `price`, `operation` FROM `stock_queue` WHERE `user_id` = '{$id}' ORDER BY `stock_name` ASC");
    $stocks = array();
    while (($stock = mysql_fetch_assoc($sql)) !== false){
        $stocks[] = array(
            'symbol'    => $stock['symbol'],
            'amount'    => $stock['amount'],
            'name'      => $stock['name'],
            'price'     => $stock['price'],
            'operation' => $stock['operation']
        );
    }

    foreach ($stocks as &$stock) {
        if($stock['operation'] === 'add'){
            $stock['operation'] = 'BUY';
        }elseif($stock['operation'] === 'sub'){
            $stock['operation'] = 'SELL';
        }
    }    

    return $stocks;
}

function after_market_buy($id, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $amount = (int)$amount;
    $totalprice = $price * $amount;
    $name = mysql_real_escape_string(html_escape($name));

    $canbuy = mysql_result(mysql_query("SELECT `user_value` FROM `users` WHERE `user_id` = '{$id}'"), 0);
    if($canbuy < $totalprice){
        return false;
    }else{
        $total = mysql_query("SELECT COUNT(`user_id`) FROM `stock_queue` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'add'");
        if(mysql_result($total, 0) == '1'){
            mysql_query("UPDATE `stock_queue` SET `amount` = `amount` + '{$amount}' WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'add'");
        }else{
            mysql_query("INSERT INTO `stock_queue` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `operation`) VALUES ('{$id}', '{$symbol}', '{$amount}', '{$name}', '{$price}', 'add')");
        }
        mysql_query("UPDATE `users` SET `user_value` = `user_value` - '{$totalprice}' - 10 WHERE `user_id` = '{$id}'");
    }
}

function after_market_short($id, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $amount = (int)$amount;
    $totalprice = $price * $amount;
    $totalprice = $totalprice + 10;
    $name = mysql_real_escape_string(html_escape($name));

    $canbuy = mysql_result(mysql_query("SELECT `user_value` FROM `users` WHERE `user_id` = '{$id}'"), 0);
    if($canbuy < $totalprice){
        return false;
    }else{
        $total = mysql_query("SELECT COUNT(`user_id`) FROM `stock_queue` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'SHORT'");
        if(mysql_result($total, 0) == '1'){
            mysql_query("UPDATE `stock_queue` SET `amount` = `amount` + '{$amount}' WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'SHORT'");
        }else{
            mysql_query("INSERT INTO `stock_queue` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `operation`) VALUES ('{$id}', '{$symbol}', '{$amount}', '{$name}', '{$price}', 'SHORT')");
        }
        mysql_query("UPDATE `users` SET `user_value` = `user_value` - '{$totalprice}' WHERE `user_id` = '{$id}'");
    }
}

function after_market_cover($id, $iid, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $iid = (int)$iid;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $amount = (int)$amount;
    $name = mysql_real_escape_string(html_escape($name));

    $cansell = mysql_query("SELECT `amount`, `stock_price` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1' AND `i_id` = '{$iid}'");
    $rows = mysql_fetch_assoc($cansell);

    $profit = (2 * $rows["stock_price"]) - $price;
    $profit = $profit * $amount;
    if($rows["amount"] < $amount){
        return false;
    }elseif($rows["amount"] >= $amount){
        mysql_query("INSERT INTO `stock_queue` (`i_id`, `user_id`, `stock_symbol`, `amount`, `stock_name`, `operation`) VALUES ('{$iid}', '{$id}', '{$symbol}', '{$amount}', '{$name}', 'COVER')");
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$profit}' - 10 WHERE `user_id` = '{$id}'");
    }
}

function after_market_sell($id, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $amount = (int)$amount;
    $price = $price * $amount;
    $price = $price + 10;
    $name = mysql_real_escape_string(html_escape($name));

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `stock_queue` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'sub'");
    if(mysql_result($total, 0) == '1'){
        $queue = mysql_query("SELECT `amount` FROM `stock_queue` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'sub'");
        $queue = mysql_result($queue, 0);
    }else{
        $queue = 0;
    }
    
    $cansell = mysql_query("SELECT `amount`, `i_id` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}'"); 
    $cansell = mysql_fetch_assoc($cansell);  
    $canselll = $cansell["amount"] - $queue;
    if($canselll < $amount){
        return false;
    }else{
        if(mysql_result($total, 0) == '1'){
            mysql_query("UPDATE `stock_queue` SET `amount` = `amount` + '{$amount}' WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `operation` = 'sub'");
        }else{
            mysql_query("INSERT INTO `stock_queue` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `operation`, `i_id`) VALUES ('{$id}', '{$symbol}', '{$amount}', '{$name}', '{$price}', 'sub', '{$cansell['i_id']}')");  
        }
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$price}' - 10 WHERE `user_id` = '{$id}'");
    }
}

function clear_queue(){
    $sql = mysql_query("SELECT `user_id` FROM `stock_queue` GROUP BY `user_id`");
    
    $rows = array();
    while (($row = mysql_fetch_assoc($sql)) !== false){
        $rows[] = array(
            'user_id'     => $row['user_id']
        );
    }

    foreach ($rows as $row) {
        $sql = mysql_query("SELECT `stock_symbol` AS `symbol`, `amount`, `stock_name` AS `name`, `stock_price` AS `price`, `operation`, `i_id` FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}'");

        $queues = array();
        while (($queue = mysql_fetch_assoc($sql)) !== false){
            $queues[] = array(
                'i_id'      => $queue['i_id'],
                'symbol'    => $queue['symbol'],
                'amount'    => $queue['amount'],
                'name'      => $queue['name'],
                'price'     => $queue['price'],
                'operation' => $queue['operation']
            );
        }

        foreach ($queues as $queue) {
            $total = mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `stock_symbol` = '{$queue['symbol']}' AND `user_id` = '{$row['user_id']}'");
            if(mysql_result($total, 0) >= 1){
                if($queue['operation'] === 'add'){
                    mysql_query("UPDATE `user_stocks` SET `amount` = `amount` + '{$queue['amount']}' WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `short` = '0'");
                    mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `operation` = 'add'");
                }elseif($queue['operation'] === 'SHORT'){
                    mysql_query("INSERT INTO `user_stocks` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `short`) VALUES ('{$row['user_id']}', '{$queue['symbol']}', '{$queue['amount']}', '{$queue['name']}', '{$queue['price']}', '1')");
                    mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `operation` = 'SHORT' AND `i_id` = '{$queue['i_id']}'");
                }else{
                    $isdepleted = mysql_query("SELECT `amount` FROM `user_stocks` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `i_id` = '{$queue['i_id']}'");
                    if(mysql_result($isdepleted, 0) - $queue['amount'] == 0){
                        mysql_query("DELETE FROM `user_stocks` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `i_id` = '{$queue['i_id']}'");
                    }else{
                        mysql_query("UPDATE `user_stocks` SET `amount` = `amount` - '{$queue['amount']}' WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `i_id` = '{$queue['i_id']}'");
                    }
                    mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND (`operation` = 'sub' OR `operation` = 'cover') AND `i_id` = '{$queue['i_id']}'");
                }
            }else{
                if($queue['operation'] === 'add'){
                    mysql_query("INSERT INTO `user_stocks` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`) VALUES ('{$row['user_id']}', '{$queue['symbol']}', '{$queue['amount']}', '{$queue['name']}', '{$queue['price']}')");
                    mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `operation` = 'add'");
                }elseif($queue['operation'] === 'SHORT'){
                    mysql_query("INSERT INTO `user_stocks` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `short`) VALUES ('{$row['user_id']}', '{$queue['symbol']}', '{$queue['amount']}', '{$queue['name']}', '{$queue['price']}', '1')");
                    mysql_query("DELETE FROM `stock_queue` WHERE `user_id` = '{$row['user_id']}' AND `stock_symbol` = '{$queue['symbol']}' AND `operation` = 'SHORT' AND `i_id` = '{$queue['i_id']}'");
                }else{
                    return false;
                }
            }
            
        }
        
    }
    
    // check payable date
    $splitsql = mysql_query("SELECT `split_symbol` AS `symbol`, `split_ratio` AS `ratio`, `split_exdate` AS `exdate`, `split_paydate` AS `paydate` FROM `splits`");
    $splits = array();
    while (($split = mysql_fetch_assoc($splitsql)) !== false){
        $splits[] = array(
            'symbol'    => $split['symbol'],
            'ratio'    => $split['ratio'],
            'exdate'      => $split['exdate'],
            'paydate'     => $split['paydate']
        );
    }

    // start new code

    $stockstring = "%27";
    foreach ($splits as $stock) {
        if(!($split['exdate'] < time()) && ($split['paydate'] < time())){
            $stockstring .= $stock["symbol"]."%27%2C%27";
        }
    }
    $stockstring .= "%27";
    // get the damned stocks
    for ($i=1;$i<=25;$i++) {
        $prices = json_decode(file_get_contents("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20symbol%20from%20yahoo.finance.quotes%20where%20symbol%20in%20({$stockstring})&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys"), true);       if(!empty($prices['query']['results']['quote'])){
            $success = true;
            break;
        }
    }
    $success = false;
    if($success){
        $final = array();
        foreach ($prices['query']['results']['quote'] as $price) {
            $name = $price["symbol"];
            $price = explode(" - ", $price["LastTradeWithTime"]);
            $price = preg_replace("/(<([^>]+)>)/i", "", $price[1]);
            $final[] = array($name => $price);
        }
    }

    // end new code


    foreach($splits AS $split){
        if($split['exdate'] < time()){
            // check share pool and do math
            $ratio = explode(':', $split['ratio']);
            // grabbing share pool where the symbol is equal
            $splitpool = mysql_query("SELECT `user_id`, `stock_amount` AS `amount`, `stock_lastprice` AS `lastprice` FROM `split_pool` WHERE `stock_symbol` = '{$split['symbol']}'");
            $finals = array();
            while (($final = mysql_fetch_assoc($splitpool)) !== false){
                $finals[] = array(
                    'amount'    => $final['amount'],
                    'user_id'   => $final['user_id'],
                    'lastprice' => $final['lastprice']
                );
            }
            foreach($finals AS $finalsplit){
                // do math & update
                $newamount = $finalsplit['amount'] / $ratio[1];
                $newamount = $newamount * $ratio[0];
                $payback = $newamount - (int)$newamount;
                $payback = $payback * $finalsplit['lastprice'];
                $newamount = (int)$newamount;
                mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$payback}' WHERE `user_id` = '{$finalsplit['user_id']}'");
                mysql_query("UPDATE `user_stocks` SET `amount` = '{$newamount}' + (`amount` - {$finalsplit['amount']}) WHERE `user_id` = '{$finalsplit['user_id']}' AND `stock_symbol` = '{$split['symbol']}'");
                mysql_query("DELETE FROM `split_pool` WHERE `user_id` = '{$finalsplit['user_id']}' AND `stock_symbol` = '{$split['symbol']}'");
            }
            mysql_query("DELETE FROM `splits` WHERE `split_symbol` = '{$split['symbol']}' AND `split_exdate` = '{$split['exdate']}'");
        }elseif($split['paydate'] < time()){
            // take snapshot of stocks into share pool
            $snapdata = mysql_query("SELECT `user_id`, `amount` FROM `user_stocks` WHERE `stock_symbol` = '{$split['symbol']}'");
            $snapshots = array();
            while (($snapshot = mysql_fetch_assoc($snapdata)) !== false){
                $snapshots[] = array(
                    'user_id'    => $snapshot['user_id'],
                    'amount'    => $snapshot['amount']
                );
            }
            
            $querystring = "INSERT INTO `split_pool` (`user_id`, `stock_symbol`, `stock_amount`, `stock_lastprice`) VALUES ";
            $i = 0;
            foreach($snapshots AS $shot){
                $pfinal = $final[$split['symbol']];
                $i++;
                if($i == count($snapshots)){
                    $querystring .= "('{$shot['user_id']}', '{$split['symbol']}', '{$shot['amount']}', '{$pfinal}')";
                }else{
                    $querystring .= "('{$shot['user_id']}', '{$split['symbol']}', '{$shot['amount']}', '{$pfinal}'), ";
                }
            }
            mysql_query($querystring);
        }
    }
    mysql_query("UPDATE `users` SET `portfolio_value` = '0.00'");
    // we want to update everyone's value according to their stocks drop/rise
    $sql = "SELECT 
                `stock_symbol` AS `symbol`
           FROM `user_stocks`
           GROUP BY `stock_symbol`";
            
    $stocks = mysql_query($sql);
    
    $rows = array();
    while (($row = mysql_fetch_assoc($stocks)) !== false){
        $rows[] = array(
            'symbol'     => $row['symbol']
        );
    }

    $stockstring = "%27";
    foreach ($rows as $stock) {
        $stockstring .= $stock["symbol"]."%27%2C%27";
    }
    $stockstring .= "%27";
    // get the damned stocks
    $success = false;
    for ($i=1;$i<=25;$i++) {
        $prices = json_decode(file_get_contents("https://query.yahooapis.com/v1/public/yql?q=select%20LastTradeWithTime%2C%20symbol%20from%20yahoo.finance.quotes%20where%20symbol%20in%20({$stockstring})&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys"), true);       if(!empty($prices['query']['results']['quote'])){
            $success = true;
            break;
        }
    }
    
    if($success){
        $final = array();
        foreach ($prices['query']['results']['quote'] as $price) {
            $name = $price["symbol"];
            $price = explode(" - ", $price["LastTradeWithTime"]);
            $price = preg_replace("/(<([^>]+)>)/i", "", $price[1]);
            $final[] = array("symbol" => $name, "price" => $price);
            mysql_query("UPDATE `users` SET `portfolio_value` = '0.00'");
        }
    }

    foreach ($final as $row) {
        // now that we have found all the required stocks, we need to gather/spread information on them
        $sql = "SELECT 
                    `user_id` AS `id`,
                    `amount`
               FROM `user_stocks`
               WHERE `stock_symbol` = '{$row['symbol']}'";
        
        $stocks = mysql_query($sql);
        
        $users = array();
        while (($user = mysql_fetch_assoc($stocks)) !== false){
            $users[] = array(
                'id'     => $user['id'],
                'amount' => $user['amount']
            );
        }

        foreach ($users as $user) {
            $price = $user['amount'] * $row["price"];
            mysql_query("UPDATE `users` SET `portfolio_value` = `portfolio_value` + '{$price}' WHERE `user_id` = '{$user['id']}'");
        }
    }
    if($success){
        mysql_query("UPDATE `users` SET `portfolio_value` = `portfolio_value` + `user_value`");
    }
}

function buy_stock($id, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $amount = (int)$amount;
    $totalprice = $price * $amount;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $name = mysql_real_escape_string(html_escape($name));

    $canbuy = mysql_query("SELECT `user_value` FROM `users` WHERE `user_id` = '{$id}'");
    $canbuy = mysql_result($canbuy, 0);
    if($canbuy < ($totalprice+10)){
        return false;
    }

    $total = mysql_query("SELECT COUNT(`user_id`) FROM `user_stocks` WHERE `stock_symbol` = '{$symbol}' AND `user_id` = '{$id}' AND `short` = '0'");
    if(mysql_result($total, 0) == '1'){
        mysql_query("UPDATE `users` SET `user_value` = `user_value` - '{$totalprice}' - 10 WHERE `user_id` = '{$id}'");
        mysql_query("UPDATE `user_stocks` SET `amount` = `amount` + '{$amount}' WHERE `stock_symbol` = '{$symbol}' AND `user_id` = '{$id}' AND `short` = '0'");
    }else{
        mysql_query("UPDATE `users` SET `user_value` = `user_value` - '{$totalprice}' - 10 WHERE `user_id` = '{$id}'");
        mysql_query("INSERT INTO `user_stocks` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`) VALUES ('{$id}', '{$symbol}', '{$amount}', '{$name}', '{$price}')");
    }

}

function sell_stock($id, $price, $symbol, $amount){
    $id = (int)$id;
    $amount = (int)$amount;
    $price = $price * $amount;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $cansell = mysql_query("SELECT `amount` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '0'");
    $cansell = mysql_result($cansell, 0);
    if($cansell < $amount){
        return false;
    }elseif($cansell == $amount){
        mysql_query("DELETE FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '0'");
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$price}' - 10 WHERE `user_id` = '{$id}'");
    }elseif($cansell > $amount){
        mysql_query("UPDATE `user_stocks` SET `amount` = `amount` - '{$amount}' WHERE `stock_symbol` = '{$symbol}' AND `user_id` = '{$id}' AND `short` = '0'");
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$price}' - 10 WHERE `user_id` = '{$id}'");
    }

}

function is_short($id, $symbol) {
    $id = (int)$id;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $total = mysql_query("SELECT COUNT(`i_id`) FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1'");
    return (mysql_result($total, 0) >= '1') ? true : false;
}

function short_stock($id, $price, $symbol, $name, $amount){
    $id = (int)$id;
    $amount = (int)$amount;
    $totalprice = $price * $amount;
    $totalprice = $totalprice + 10;
    $symbol = strtoupper(mysql_real_escape_string(html_escape($symbol)));
    $name = mysql_real_escape_string(html_escape($name));

    $canbuy = mysql_query("SELECT `user_value` FROM `users` WHERE `user_id` = '{$id}'");
    $canbuy = mysql_result($canbuy, 0);
    if($canbuy < ($totalprice+10)){
        return false;
    }

    mysql_query("UPDATE `users` SET `user_value` = `user_value` - '{$totalprice}' - 10 WHERE `user_id` = '{$id}'");
    mysql_query("INSERT INTO `user_stocks` (`user_id`, `stock_symbol`, `amount`, `stock_name`, `stock_price`, `short`) VALUES ('{$id}', '{$symbol}', '{$amount}', '{$name}', '{$price}', '1')");

}

function cover_stock($id, $iid, $price, $symbol, $amount){
    $id = (int)$id;
    $iid = (int)$iid;
    $amount = (int)$amount;
    $symbol = mysql_real_escape_string(html_escape($symbol));

    $cansell = mysql_query("SELECT `amount`, `stock_price` FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1' AND `i_id` = '{$iid}'");
    $rows = mysql_fetch_assoc($cansell);

    $profit = (2 * $rows["stock_price"]) - $price;
    $profit = $profit * $amount;

    if($rows["amount"] < $amount){
        return false;
    }elseif($rows["amount"] == $amount){
        mysql_query("DELETE FROM `user_stocks` WHERE `user_id` = '{$id}' AND `stock_symbol` = '{$symbol}' AND `short` = '1' AND `i_id` = '{$iid}'");
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$profit}' - 10 WHERE `user_id` = '{$id}'");
    }elseif($rows["amount"] > $amount){
        mysql_query("UPDATE `user_stocks` SET `amount` = `amount` - '{$amount}' WHERE `stock_symbol` = '{$symbol}' AND `user_id` = '{$id}' AND `short` = '1' AND `i_id` = '{$iid}'");
        mysql_query("UPDATE `users` SET `user_value` = `user_value` + '{$profit}' - 10 WHERE `user_id` = '{$id}'");
    }

}

?>
