<?php

// Centralize includes
// Will remove the need to somehow bypass the login redirects

$path = dirname(__FILE__);
$page = substr(end(explode('/', $_SERVER['SCRIPT_NAME'])), 0, -4);
session_start();
session_set_cookie_params(86400,"/");
ini_set('session.gc_maxlifetime', 86400);
date_default_timezone_set('America/New_York');

error_reporting(error_reporting() & (-1 ^ E_DEPRECATED));
ini_set('display_errors', 0);
ini_set("log_errors", 1);

$blacklist = array('CRZ12.NYB', 'GIM13.CME', 'DJM13.CBT', 'ESM13.CME', 'YMM13.CBT', 'NQM13.CME', 'SPM13.CME', 'AEX.AS', 'FPXAA.PR', 'MICEXINDEXCF.ME', 'GD.AT', 'DOW', 'NASDAQ', '^GSPC', '^DJA', '^DJI', '^DJT', '^DJU', '^NYA', '^NIN', '^NTM', '^NUS', '^NWL', '^IXBK', '^NBI', '^IXIC', '^IXK', '^IXF', '^IXID', '^IXIS', '^IXFN', '^IXUT', '^IXTR', '^NDX', '^TV.O', '^OEX', '^SML', '^SPSUPX', '^MID', '^BATSK', '^DWC', '^IIX', '^XAX', '^XMI', '^NWX', '^PSE', '^SOX', '^RUI', '^RUT', '^RUA', '^IRX', '^TNX', '^TYX', '^FVX', 'GIQ13.CME', '^XAU', 'DJU13.CBT', 'ESU13.CME', 'YMU13.CBT', 'NQU13.CME', 'SPU13.CME', '^MERV', '^BVSP', '^GSPTSE', '^MXX', '^AORD', '^SSEC', '^HSI', '^BSESN', '^JKSE', '^KLSE', '^N225', '^NZ50', '^STI', '^KS11', '^TWII', '^ATX', '^BFX', '^FCHI', '^GDAXI', 'AEX.AS', '^OSEAX', '^OMXSPI', '^SSMI', '^FTSE', 'FPXAA.PR', 'MICEXINDEXCF.ME', 'GD.AT', 'CMA.CA', '^TA100');

mysql_connect('localhost', 'user', 'password');
mysql_select_db('seed');

$marketclosed = FALSE;
$market = null;

include("{$path}/../core/inc/globals.inc.php");
include("{$path}/../core/inc/user.inc.php");
include("{$path}/../core/inc/teams.inc.php");
include("{$path}/../core/inc/search.inc.php");
include("{$path}/../core/inc/stocks.inc.php");
include("{$path}/../core/inc/education.inc.php");
include("{$path}/../core/inc/staff.inc.php");
include("{$path}/../core/inc/holidays.inc.php");
include("{$path}/../core/inc/sites.inc.php");

// if((date("Hi", time())) < 930 || (date("Hi", time())) > 1600 || date('N', time()) >= 6){
// 	$marketclosed = TRUE;
// 	$market = "<div class='alert alert-warning'>
//   <strong>Heads up!</strong> The market is not open right now. All transactions will go through after it reopens.
// </div>";
// }

if ( ! function_exists('html_escape'))
{
    function html_escape($var)
    {
        if (is_array($var))
        {
            return array_map('html_escape', $var);
        }
        else
        {
            return htmlspecialchars($var, ENT_QUOTES);
        }
    }
}


// $marketclosed = FALSE;
// $market = null;

?>
