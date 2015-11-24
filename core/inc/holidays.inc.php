<?php

// holiday array structure array(EVENT, START, END)
// http://www.rightline.net/calendar/market-holidays.html
$holidays = array(
array("Thanksgiving", 1417064400, 1417150800),
array("Christmas", 1419483600, 1419570000),
array("New Years Day", 1420088400, 1420174800),
array("Martin Luther King Jr. Day", 1421643600, 1421730000),
array("Washington's Birthday", 1424062800, 1424149200),
array("Good Friday", 1428033600, 1428120000)
);

foreach ($holidays as $index => $holiday) {
	if(($holiday[1] < time()) && (time() < $holiday[2])){
		$marketclosed = TRUE;
			$market = "<div class='alert'>
		  <a href='?close' class='close'>&times;</a>
		  <strong>Heads up!</strong> The market is closed due to the observation of {$holiday[0]}. All transactions will go through tomorrow.
		</div>";
	}
}

?>
