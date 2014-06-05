<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API Oanda</title>
</head>

<body>

<?php
$time_start=microtime(true);
include(dirname( dirname (__FILE__))."/config.php");
include(dirname( dirname (__FILE__))."/class_lib.php");


$oanda = new oandaApi(TOKEN_OANDA , ACCOUNT_NUMBER , "real");


$instruments=array("EUR_USD","USD_CHF", "GBP_USD");
$instrument = "EUR_USD";
$count=5;
$timeFrame="H1";
$candleFormat = "midpoint";


$what=1;

switch ($what) {
	case 1 : $return=$oanda->getCurrentPrices($instruments); break;
	case 2 : $return=$oanda->getInstrumentList($instruments); break;
	case 3 : $return=$oanda->getHistoricalPrices($instrument, $timeFrame, $count, $candleFormat); break;
}

echo "<pre>";
print_r($return);
echo "</pre>";

$time_end=microtime(true);
echo "<p>duration : ".($time_end-$time_start)." seconds</p>"
?>
</body>
</html>