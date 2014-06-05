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
	 
	/*
	 *	
	 *	$environment = "sandbox" OR "practice" OR "real"
	 */
	function __construct($token , $accountId , $environment="sandbox")
	{
		$this->token=$token;
		$this->accountId=$accountId;
		$this->environment=$environment;
	}

	/*
	 *	get instrument list
	 *	http://developer.oanda.com/docs/v1/rates/#get-an-instrument-list
	 */
	function getInstrumentList($instruments)
	{
		$url="instruments";
		$parameters=array("accountId"=>$this->accountId,"instruments"=>implode(",",$instruments));
		
		return $this->sendGetRequest($parameters,$url);
	}

	/*
	 *	get current prices
	 *	http://developer.oanda.com/docs/v1/rates/#get-current-prices
	 */
	function getCurrentPrices($instruments)
	{
		$url="prices";
		$parameters=array("instruments"=>implode(",",$instruments));
		
		return $this->sendGetRequest($parameters,$url);
	}

	/*
	 *	get historical prices
	 *	http://developer.oanda.com/docs/v1/rates/#retrieve-instrument-history
	 */
	function getHistoricalPrices($instrument , $timeFrame , $count , $candleFormat = "midpoint")
	{
		$url="candles";
		$parameters=array("instrument"=>$instrument,"granularity"=>$timeFrame,"count"=>$count,"candleFormat"=>$candleFormat);
		
		return $this->sendGetRequest($parameters,$url);
	}
	
	private function sendGetRequest($parameters, $url) {
		$param=http_build_query($parameters);
		$opts = array(
	 		'http'=>array(
				'method'=>'GET',
				'header'=>	"Authorization: Bearer ".$this->token."\r\n" .
							"Accept-Encoding: gzip, deflate\r\n",
							"Connection: Keep-Alive\r\n"
		  	)
		);
		$context = stream_context_create($opts);
		$response = @file_get_contents(oandaApi::$apiUrls[$this->environment].$url."?".$param,false,$context);
		$responseHeader = $http_response_header;
		
		return $response;
	}
}

?>