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


$oanda = new oandaApi(TOKEN_OANDA , ACCOUNT_NUMBER , ACCOUNT_TYPE, true);

$return="";
$instruments=array("EUR_USD");
$instrument = "EUR_USD";
$count=3;
$timeFrame="H1";
$candleFormat = "midpoint";
$username = USERNAME;
$idAccount=ACCOUNT_NUMBER;
$maxId = 1900000000;

$units=2;
$side="sell";
$type="limit";
//$type="market";
$expiry=gmdate("Y-m-d\TH:i:sP", strtotime("+ 1 hour"));
$price=1.500;
$price2=1.5001;
$lowerBound=$price*.999;
$upperBound=$price*1.001;
$stopLoss=1.5050;
$takeProfit=1.4800;
$trailingStop=50;
$idOrder=574462816;
$idtrade=132;

$what=(isset($_GET['i']) ? $_GET['i'] : 1);

switch ($what) {
	case 1 : $return=$oanda->getCurrentPrices($instruments); break;
	case 2 : $return=$oanda->getInstrumentList($instruments); break;
	case 3 : $return=$oanda->getHistoricalPrices($instrument, $timeFrame, $count, $candleFormat); break;
	case 4 : $return=$oanda->getAccounts($username); break;
	case 5 : $return=$oanda->getAccountInformation($idAccount); break;
	case 6 : $return=$oanda->getOrders($idAccount, $maxId, $count, $instrument); break;
	case 7 : $return=$oanda->createOrder($idAccount, $instrument, $units, $side, $type, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop); break; 
	//case 7 : $return=$oanda->createOrder($idAccount, $instrument, $units, $side, $type, "", "", "", "", "", "", ""); break; 
	case 8 : $return=$oanda->getInformationOrder($idAccount, $idOrder); break; 
	case 9 : $return=$oanda->modifyOrder($idAccount, $idOrder, $units, $expiry, $price2, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop); break;
	case 10: $return=$oanda->deleteOrder($idAccount, $idOrder); break; 
	case 11: $return=$oanda->getTrades($idAccount, $maxId, $count, $instrument); break; 
	case 12: $return=$oanda->getInformationTrade($idAccount, $idTrade); break; 
	case 13: $return=$oanda->modifyTrade($idAccount, $idTrade, $stopLoss, $takeProfit, $trailingStop); break; 
	case 14: $return=$oanda->closeTrade($idAccount, $idTrade); break; 
}

echo "<pre>";
print_r($return);
echo "</pre>";

$time_end=microtime(true);
echo "<p>duration : ".($time_end-$time_start)." seconds</p>";
?>

<h2>Rates</h2>
<ul>
	<li><a href="?i=1"><?php echo "getCurrentPrices($instruments)"; ?></a></li>
    <li><a href="?i=2"><?php echo "getInstrumentList($instruments)"; ?></a></li>
    <li><a href="?i=3"><?php echo "getHistoricalPrices($instrument, $timeFrame, $count, $candleFormat)"; ?></a></li>
</ul>
<h2>Accounts</h2>
<ul>
	<li><a href="?i=4"><?php echo "getAccounts($username)"; ?></a></li>
	<li><a href="?i=5"><?php echo "getAccountInformation($idAccount)"; ?></a></li>
</ul>
<h2>Orders</h2>
<ul>
	<li><a href="?i=6"><?php echo "getOrders($idAccount, $maxId, $count, $instrument)"; ?></a></li>
    <li><a href="?i=7"><?php echo "createOrder($idAccount, $instrument, $units, $side, $type, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop)"; ?></a></li>
    <li><a href="?i=8"><?php echo "getInformationOrder($idAccount, $idOrder)"; ?></a></li>
    <li><a href="?i=9"><?php echo "modifyOrder($idAccount, $idOrder, $units, $expiry, $price2, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop)"; ?></a></li>
    <li><a href="?i=10"><?php echo "deleteOrder($idAccount, $idOrder)"; ?></a></li>
</ul>
<h2>Trades</h2>
<ul>
	<li><a href="?i=11"><?php echo "getTrades($idAccount, $maxId, $count, $instrument)"; ?></a></li>
    <li><a href="?i=12"><?php echo "getInformationTrade($idAccount, $idTrade)"; ?></a></li>
    <li><a href="?i=13"><?php echo "modifyTrade($idAccount, $idTrade, $stopLoss, $takeProfit, $trailingStop)"; ?></a></li>
    <li><a href="?i=14"><?php echo "closeTrade($idAccount, $idTrade)"; ?></a></li>
</ul>
</body>
</html>