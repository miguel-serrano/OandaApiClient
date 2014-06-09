<?php

class oandaApi{
	static $apiUrls = array(
		"sandbox"=>"http://api-sandbox.oanda.com/v1/",
		"practice"=>"https://api-fxpractice.oanda.com/v1/",
		"real"=>"https://api-fxtrade.oanda.com/v1/"
	);

	private $environment;
	private $token;
	private $debug;

	public $accountId;	 
	/*
	 *	
	 *	$environment = "sandbox" OR "practice" OR "real"
	 */
	function __construct($token , $environment="sandbox", $debug=false)
	{
		$this->token=$token;
		$this->environment=$environment;
		$this->debug=$debug;
	}

	function setAccountId($accountId){
		$this->accountId=$accountId;
	}
	/***********************************************************************
	 *	rates
	 ***********************************************************************/	
	/*	
	 *	get instrument list
	 *	http://developer.oanda.com/docs/v1/rates/#get-an-instrument-list
	 */
	function getInstrumentList($instruments=array())
	{
		$url="instruments";
		$parameters=array();
		$parameters["accountId"]=$this->accountId;
		if (count($instruments)!=0) {$parameters["instruments"]=implode(",",$instruments);}
		
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
	function getHistoricalPrices($instrument , $timeFrame=NULL , $count=NULL , $candleFormat = NULL)
	{
		$url="candles";
		$parameters=array();
		$parameters["instrument"]=$instrument;
		if (!is_null($timeFrame)) 	{$parameters["granularity"]=$timeFrame;}		
		if (!is_null($count)) 		{$parameters["count"]=$count;}		
		if (!is_null($candleFormat)){$parameters["candleFormat"]=$candleFormat;}		
		
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
	function getAccount()
	{
		$url="accounts/".$this->accountId;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/***********************************************************************
	 *	orders
	 ***********************************************************************/	
	/*
	 *	get orders for an account
	 *	http://developer.oanda.com/docs/v1/orders/#get-orders-for-an-account
	 */
	function getOrders($count = NULL, $instrument = NULL, $maxId = NULL)
	{
		$url="accounts/".$this->accountId."/orders";
		$parameters=array();
		if (!is_null($count)) 		{$parameters["count"]=$count;}		
		if (!is_null($instrument))	{$parameters["instrument"]=$instrument;}		
		if (!is_null($maxId)) 		{$parameters["maxId"]=$maxId;}		
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	create a new order
	 *	http://developer.oanda.com/docs/v1/orders/#create-a-new-order
	 */
	function createOrder($instrument, $units, $side, $type, $expiry=NULL, $price=NULL, $lowerBound=NULL, $upperBound=NULL, $stopLoss=NULL, $takeProfit=NULL, $trailingStop=NULL)
	{
		$url="accounts/".$this->accountId."/orders";
		$parameters=array();
		$parameters["instrument"]=$instrument;
		$parameters["units"]=$units;
		$parameters["side"]=$side;
		$parameters["type"]=$type;
		if (!is_null($expiry)) 		{$parameters["expiry"]=$expiry;}
		if (!is_null($price)) 		{$parameters["price"]=$price;}
		if (!is_null($lowerBound)) 	{$parameters["lowerBound"]=$lowerBound;}
		if (!is_null($upperBound)) 	{$parameters["upperBound"]=$upperBound;}
		if (!is_null($stopLoss)) 	{$parameters["stopLoss"]=$stopLoss;}
		if (!is_null($takeProfit)) 	{$parameters["takeProfit"]=$takeProfit;}
		if (!is_null($trailingStop)){$parameters["trailingStop"]=$trailingStop;}
		
		return $this->sendRequest("POST",$parameters,$url);
	}

	/*
	 *	get information for an order
	 *	http://developer.oanda.com/docs/v1/orders/#get-information-for-an-order
	 */
	function getOrder($idOrder)
	{
		$url="accounts/".$this->accountId."/orders/".$idOrder;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}
	
	/*
	 *	modify an existing order
	 *	http://developer.oanda.com/docs/v1/orders/#modify-an-existing-order
	 */
	function modifyOrder($idOrder, $units=NULL, $expiry=NULL, $price=NULL, $lowerBound=NULL, $upperBound=NULL, $stopLoss=NULL, $takeProfit=NULL, $trailingStop=NULL)
	{
		$url="accounts/".$this->accountId."/orders/".$idOrder;
		$parameters=array(	"units"=>$units,
							"expiry"=>$expiry,
							"price"=>$price,
							"lowerBound"=>$lowerBound,
							"upperBound"=>$upperBound,
							"stopLoss"=>$stopLoss,
							"takeProfit"=>$takeProfit,
							"trailingStop"=>$trailingStop
							);

		$parameters=array();
		if (!is_null($units)) 		{$parameters["units"]=$units;}
		if (!is_null($expiry)) 		{$parameters["expiry"]=$expiry;}
		if (!is_null($price)) 		{$parameters["price"]=$price;}
		if (!is_null($lowerBound)) 	{$parameters["lowerBound"]=$lowerBound;}
		if (!is_null($upperBound)) 	{$parameters["upperBound"]=$upperBound;}
		if (!is_null($stopLoss)) 	{$parameters["stopLoss"]=$stopLoss;}
		if (!is_null($takeProfit)) 	{$parameters["takeProfit"]=$takeProfit;}
		if (!is_null($trailingStop)){$parameters["trailingStop"]=$trailingStop;}
		
		return $this->sendRequest("PATCH",$parameters,$url);
	}

	/*
	 *	Close an order
	 *	http://developer.oanda.com/docs/v1/orders/#close-an-order
	 */
	function deleteOrder($idOrder)
	{
		$url="accounts/".$this->accountId."/orders/".$idOrder;
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
	function getTrades($count = NULL, $instrument = NULL, $maxId = NULL)
	{
		$url="accounts/".$this->accountId."/trades";
		$parameters=array();
		if (!is_null($count)) 		{$parameters["count"]=$count;}
		if (!is_null($instrument)) 	{$parameters["instrument"]=$instrument;}
		if (!is_null($maxId)) 		{$parameters["maxId"]=$maxId;}
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get information on a specific trade
	 *	http://developer.oanda.com/docs/v1/trades/#get-information-on-a-specific-trade
	 */
	function getTrade($idTrade)
	{
		$url="accounts/".$this->accountId."/trades/".$idTrade;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	modify an existing trade
	 *	http://developer.oanda.com/docs/v1/trades/#modify-an-existing-trade
	 */
	function modifyTrade($idTrade, $stopLoss=NULL, $takeProfit=NULL, $trailingStop=NULL)
	{
		$url="accounts/".$this->accountId."/trades/".$idTrade;
		$parameters=array();
		if (!is_null($stopLoss)) 	 {$parameters["stopLoss"]=$stopLoss;}
		if (!is_null($takeProfit)) 	 {$parameters["takeProfit"]=$takeProfit;}
		if (!is_null($trailingStop)) {$parameters["trailingStop"]=$trailingStop;}
		
		return $this->sendRequest("PATCH",$parameters,$url);
	}

	/*
	 *	close an open trade
	 *	http://developer.oanda.com/docs/v1/trades/#close-an-open-trade
	 */
	function closeTrade($idTrade)
	{
		$url="accounts/".$this->accountId."/trades/".$idTrade;
		$parameters=array();
		
		return $this->sendRequest("DELETE",$parameters,$url);
	}

	/***********************************************************************
	 *	positions
	 ***********************************************************************/	
	/*
	 *	get a list of all open positions
	 *	http://developer.oanda.com/docs/v1/positions/#get-a-list-of-all-open-positions
	 */
	function getPositions()
	{
		$url="accounts/".$this->accountId."/positions";
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get the position for an instrument
	 *	http://developer.oanda.com/docs/v1/positions/#get-the-position-for-an-instrument
	 */
	function getPosition($instrument)
	{
		$url="accounts/".$this->accountId."/positions/".$instrument;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	close an existing position
	 *	http://developer.oanda.com/docs/v1/positions/#close-an-existing-position
	 */
	function closePosition($instrument)
	{
		$url="accounts/".$this->accountId."/positions/".$instrument;
		$parameters=array();
		
		return $this->sendRequest("DELETE",$parameters,$url);
	}

	/***********************************************************************
	 *	Transactions
	 ***********************************************************************/	
	/*
	 *	get transaction history
	 *	http://developer.oanda.com/docs/v1/transactions/#get-transaction-history
	 */
	function getTransactions($count = NULL, $instrument = NULL, $maxId = NULL, $minId = NULL, $ids = NULL)
	{
		$url="accounts/".$this->accountId."/transactions";
		$parameters=array();
		if (!is_null($count)) 		{$parameters["count"]=$count;}
		if (!is_null($instrument)) 	{$parameters["instrument"]=$instrument;}
		if (!is_null($maxId)) 		{$parameters["maxId"]=$maxId;}
		if (!is_null($minId)) 		{$parameters["minId"]=$minId;}
		if (!is_null($ids)) 		{$parameters["ids"]=$ids;}
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get information for a transaction
	 *	http://developer.oanda.com/docs/v1/transactions/#get-information-for-a-transaction
	 */
	function getTransaction($idTransaction)
	{
		$url="accounts/".$this->accountId."/transactions/".$idTransaction;
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
	}

	/*
	 *	get full account history
	 *	http://developer.oanda.com/docs/v1/transactions/#get-full-account-history
	 */
	function getAllTransactions()
	{
		$url="accounts/".$this->accountId."/alltransactions";
		$parameters=array();
		
		return $this->sendRequest("GET",$parameters,$url);
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