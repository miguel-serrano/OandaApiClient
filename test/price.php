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


$oanda = new oandaApi(TOKEN_OANDA , ACCOUNT_TYPE, true);
$oanda->setAccountId(ACCOUNT_NUMBER);

$return="";
$instruments=array("EUR_USD","USD_CHF");
$instrument = "USD_CHF";
$count=3;
$timeFrame="H1";
$candleFormat = "midpoint";
$username = USERNAME;
$maxId = NULL;

$units=2;
$side="sell";
$type1="limit";
$type2="market";
$expiry=gmdate("Y-m-d\TH:i:sP", strtotime("+ 1 hour"));
$price=1.500;
$price2=1.5001;
$lowerBound=$price*.999;
$upperBound=$price*1.001;
$stopLoss=1.5050;
$takeProfit=1.4800;
$trailingStop=50;
$idOrder=574512617;
$idTrade=574539171;
$idTransaction=1;

$what=(isset($_GET['i']) ? $_GET['i'] : 1);

switch ($what) {
	case 0 : $return=$oanda->getCurrentPrices($instruments); break;
	case 1 : $return=$oanda->getInstrumentList(); break;
	case 2 : $return=$oanda->getInstrumentList($instruments); break;
	case 3 : $return=$oanda->getHistoricalPrices($instrument, $timeFrame, $count, $candleFormat); break;

	case 10: $return=$oanda->getAccounts($username); break;
	case 11: $return=$oanda->getAccount(); break;

	case 20: $return=$oanda->getOrders($count, $instrument, $maxId); break;
	case 21: $return=$oanda->createOrder($instrument, $units, $side, $type1, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop); break; 
	case 22: $return=$oanda->createOrder($instrument, $units, $side, $type2); break; 
	case 23: $return=$oanda->getOrder($idOrder); break; 
	case 24: $return=$oanda->modifyOrder($idOrder, NULL, NULL, $price2); break;
	case 25: $return=$oanda->deleteOrder($idOrder); break; 

	case 30: $return=$oanda->getTrades($count); break; 
	case 31: $return=$oanda->getTrade($idTrade); break; 
	case 32: $return=$oanda->modifyTrade($idTrade, NULL, NULL, $trailingStop); break; 
	case 33: $return=$oanda->closeTrade($idTrade); break; 

	case 40: $return=$oanda->getPositions(); break; 
	case 41: $return=$oanda->getPosition($instrument); break; 
	case 42: $return=$oanda->closePosition($instrument); break; 

	case 50: $return=$oanda->getTransactions($count); break; 
	case 51: $return=$oanda->getTransaction($idTransaction); break; 
	case 52: $return=$oanda->getAllTransactions(); break; 
}

echo "<pre>";
print_r($return);
echo "</pre>";

$time_end=microtime(true);
echo "<p>duration : ".($time_end-$time_start)." seconds</p>";
?>

<h2>Rates</h2>
<ul>
	<li><a href="?i=0"><?php echo "getCurrentPrices($instruments)"; ?></a></li>
    <li><a href="?i=1"><?php echo "getInstrumentList()"; ?></a> (ALL)</li>
    <li><a href="?i=2"><?php echo "getInstrumentList($instruments)"; ?></a></li>
    <li><a href="?i=3"><?php echo "getHistoricalPrices($instrument, $timeFrame, $count, $candleFormat)"; ?></a></li>
</ul>
<h2>Accounts</h2>
<ul>
	<li><a href="?i=10"><?php echo "getAccounts($username)"; ?></a></li>
	<li><a href="?i=11"><?php echo "getAccount()"; ?></a></li>
</ul>
<h2>Orders</h2>
<ul>
	<li><a href="?i=20"><?php echo "getOrders($count, $instrument, $maxId)"; ?></a></li>
    <li>LIMIT <a href="?i=21"><?php echo "createOrder($instrument, $units, $side, $type1, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop)"; ?></a></li>
    <li>MARKET <a href="?i=22"><?php echo "createOrder($instrument, $units, $side, $type2)"; ?></a></li>
    <li><a href="?i=23"><?php echo "getOrder($idOrder)"; ?></a></li>
    <li><a href="?i=24"><?php echo "modifyOrder($idOrder, NULL, NULL, $price2)"; ?></a></li>
    <li><a href="?i=25"><?php echo "deleteOrder($idOrder)"; ?></a></li>
</ul>
<h2>Trades</h2>
<ul>
	<li><a href="?i=30"><?php echo "getTrades($count)"; ?></a></li>
    <li><a href="?i=31"><?php echo "getTrade($idTrade)"; ?></a></li>
    <li><a href="?i=32"><?php echo "modifyTrade($idTrade, NULL, NULL, $trailingStop)"; ?></a></li>
    <li><a href="?i=33"><?php echo "closeTrade($idTrade)"; ?></a></li>
</ul>
<h2>Positions</h2>
<ul>
	<li><a href="?i=40"><?php echo "getPositions()"; ?></a></li>
    <li><a href="?i=41"><?php echo "getPosition($instrument)"; ?></a></li>
    <li><a href="?i=42"><?php echo "closePosition($instrument)"; ?></a></li>
</ul>
<h2>Transactions</h2>
<ul>
	<li><a href="?i=50"><?php echo "getTransactions($count)"; ?></a></li>
    <li><a href="?i=51"><?php echo "getTransaction($idTransaction)"; ?></a></li>
    <li><a href="?i=52"><?php echo "getAllTransactions($idTransaction)"; ?></a></li>
</ul>
</body>
</html>