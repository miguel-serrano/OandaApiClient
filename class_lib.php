<?php

class oandaApi{
	static $apiUrls = array(
		"sandbox"=>"http://api-sandbox.oanda.com/v1/",
		"practice"=>"https://api-fxpractice.oanda.com/v1/",
		"real"=>"https://api-fxtrade.oanda.com/v1/"
	);

	private $environment;
	private $token;
	private $accountId;
	private $debug;
	 
	/*
	 *	
	 *	$environment = "sandbox" OR "practice" OR "real"
	 */
	function __construct($token , $accountId , $environment="sandbox", $debug=false)
	{
		$this->token=$token;
		$this->accountId=$accountId;
		$this->environment=$environment;
		$this->debug=$debug;
	}

	/***********************************************************************
	 *	rates
	 ***********************************************************************/	
	/*	
	 *	get instrument list
	 *	http://developer.oanda.com/docs/v1/rates/#get-an-instrument-list
	 */
	function getInstrumentList($instruments)
	{
		$url="instruments";
		$parameters=array("accountId"=>$this->accountId,"instruments"=>implode(",",$instruments));
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get current prices
	 *	http://developer.oanda.com/docs/v1/rates/#get-current-prices
	 */
	function getCurrentPrices($instruments)
	{
		$url="prices";
		$parameters=array("instruments"=>implode(",",$instruments));
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get historical prices
	 *	http://developer.oanda.com/docs/v1/rates/#retrieve-instrument-history
	 */
	function getHistoricalPrices($instrument , $timeFrame , $count , $candleFormat = "midpoint")
	{
		$url="candles";
		$parameters=array("instrument"=>$instrument,"granularity"=>$timeFrame,"count"=>$count,"candleFormat"=>$candleFormat);
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/***********************************************************************
	 *	accounts
	 ***********************************************************************/	
	/*
	 *	get accounts for a user
	 *	http://developer.oanda.com/docs/v1/accounts/#get-accounts-for-a-user
	 */
	function getAccounts($username)
	{
		$url="accounts";
		$parameters=array("username"=>$username);
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get account information
	 *	http://developer.oanda.com/docs/v1/accounts/#get-account-information
	 */
	function getAccountInformation($idAccount)
	{
		$url="accounts/".$idAccount;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get orders for an account
	 *	http://developer.oanda.com/docs/v1/orders/#get-orders-for-an-account
	 */
	function getOrders($idAccount, $maxId = "", $count = 50, $instrument = "")
	{
		$url="accounts/".$idAccount."/orders";
		$parameters=array("maxId"=>$maxId, "count"=>$count, "instrument"=>$instrument);
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/***********************************************************************
	 *	orders
	 ***********************************************************************/	
	/*
	 *	create a new order
	 *	http://developer.oanda.com/docs/v1/orders/#create-a-new-order
	 */
	function createOrder($idAccount, $instrument, $units, $side, $type, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop)
	{
		$url="accounts/".$idAccount."/orders";
		$parameters=array(	"instrument"=>$instrument,
							"units"=>$units,
							"side"=>$side,
							"type"=>$type,
							"expiry"=>$expiry,
							"price"=>$price,
							"lowerBound"=>$lowerBound,
							"upperBound"=>$upperBound,
							"stopLoss"=>$stopLoss,
							"takeProfit"=>$takeProfit,
							"trailingStop"=>$trailingStop
							);
		
		return $this->sendRequest("POST",$parameters,$url);
	}

	/*
	 *	get information for an order
	 *	http://developer.oanda.com/docs/v1/orders/#get-information-for-an-order
	 */
	function getInformationOrder($idAccount, $idOrder)
	{
		$url="accounts/".$idAccount."/orders/".$idOrder;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}
	
	/*
	 *	modify an existing order
	 *	http://developer.oanda.com/docs/v1/orders/#modify-an-existing-order
	 */
	function modifyOrder($idAccount, $idOrder, $units, $expiry, $price, $lowerBound, $upperBound, $stopLoss, $takeProfit, $trailingStop)
	{
		$url="accounts/".$idAccount."/orders/".$idOrder;
		$parameters=array(	"units"=>$units,
							"expiry"=>$expiry,
							"price"=>$price,
							"lowerBound"=>$lowerBound,
							"upperBound"=>$upperBound,
							"stopLoss"=>$stopLoss,
							"takeProfit"=>$takeProfit,
							"trailingStop"=>$trailingStop
							);
		
		return $this->sendRequest("PATCH",$parameters,$url);
	}

	/*
	 *	Close an order
	 *	http://developer.oanda.com/docs/v1/orders/#close-an-order
	 */
	function deleteOrder($idAccount, $idOrder)
	{
		$url="accounts/".$idAccount."/orders/".$idOrder;
		$parameters=array();
		
		return $this->sendRequest("DELETE",$parameters,$url);
	}

	/***********************************************************************
	 *	trades
	 ***********************************************************************/	
	/*
	 *	get a list of open trades
	 *	http://developer.oanda.com/docs/v1/trades/#get-a-list-of-open-trades
	 */
	function getTrades($idAccount, $maxId = "", $count = 50, $instrument = "")
	{
		$url="accounts/".$idAccount."/trades";
		$parameters=array("maxId"=>$maxId, "count"=>$count, "instrument"=>$instrument);
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get information on a specific trade
	 *	http://developer.oanda.com/docs/v1/trades/#get-information-on-a-specific-trade
	 */
	function getInformationTrade($idAccount, $idTrade)
	{
		$url="accounts/".$idAccount."/trades/".$idTrade;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	modify an existing trade
	 *	http://developer.oanda.com/docs/v1/trades/#modify-an-existing-trade
	 */
	function modifyTrade($idAccount, $idTrade, $stopLoss, $takeProfit, $trailingStop)
	{
		$url="accounts/".$idAccount."/trades/".$idTrade;
		$parameters=array("stopLoss"=>$stopLoss, "takeProfit"=>$takeProfit, "trailingStop"=>$trailingStop);
		
		return $this->sendRequest("PATCH",$parameters,$url);
	}

	/*
	 *	close an open trade
	 *	http://developer.oanda.com/docs/v1/trades/#close-an-open-trade
	 */
	function closeTrade($idAccount, $idTrade)
	{
		$url="accounts/".$idAccount."/trades/".$idTrade;
		$parameters=array();
		
		return $this->sendRequest("DELETE",$parameters,$url);
	}

	/***********************************************************************
	 *	send a request
	 ***********************************************************************/	
	private function sendRequest($method, $parameters, $url) {
		
		$param=http_build_query($parameters);
		$requestUrl=oandaApi::$apiUrls[$this->environment].$url;
		
		if (strtoupper($method)=="GET") {
			$content="";
			$requestUrl.="?".$param;
		}else{
			$content=$param;
		}
		
		$key=md5($requestUrl.$this->token);
		$memcache = new Memcache;
		$eTag = $memcache->get($key);
		if ($eTag !== false) {
			$eTagHeader="If-None-Match: ".$eTag['ETag']."";
		}else{
			$eTagHeader="";
		}

		$opts = array(
	 		'http'=>array(
				'method'=>	$method,
				'content'=> $content,
				'header'=>	"Authorization: Bearer ".$this->token."\r\n" .
							"Accept-Encoding: gzip, deflate\r\n".
							"Content-Type:  application/x-www-form-urlencoded\r\n".
							$eTagHeader
		  	)
		);
		$context = stream_context_create($opts);
		$response = @file_get_contents($requestUrl,false,$context);
		$responseHeader = $this->handleHeader($http_response_header);

		switch ($responseHeader["statusCode"]) {
			case 200: 	$memcache->set($key , array('ETag'=>$responseHeader['ETag'], 'response'=>$response) , NULL , 60*60);
						break;
			case 304: 	$response=$eTag['response'];
						break;
		}

		$this->debugLog($eTagHeader);
		$this->debugLog($opts['http']['content']);
		$this->debugLog($key);
		$this->debugLog($method);
		$this->debugLog($requestUrl);
		$this->debugLog($responseHeader);
		
		return $response;
	}
	
	/*
	 *	handle the HTTP header
	 */	
	private function handleHeader($headers) {
		$return=array();
		foreach ($headers as $key=>$header) {
			$r=explode(":",$header,2);
			if (isset ($r[1])) {
				$return[$r[0]]=$r[1];
			}elseif ($key==0){
				$return["statusCode"]=substr($header, 9, 3);;
			}
		}
		return $return;
	}
	
	/*
	 *	Debug
	 */		
	private function debugLog($str){
		if ($this->debug) {
			if (is_array($str)) {
				$return="";
				foreach ($str as $key=>$value) {
					if (is_array($value)) {
						$return.=$key. ' => ' .implode($value,' | ').'\r\n';
					}
					else{
						$return.=$key. ' => ' .$value. '\n';
					}
				}
			}else{
				$return=$str;
			}
			echo "<script> console.log('$return');</script>";
		}
	}
}

?>