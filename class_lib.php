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
		$requestUrl=oandaApi::$apiUrls[$this->environment].$url."?".$param;
		
		
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
				'method'=>'GET',
				'header'=>	"Authorization: Bearer ".$this->token."\r\n" .
							"Accept-Encoding: gzip, deflate\r\n".
							$eTagHeader
		  	)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($requestUrl,false,$context);
		$responseHeader = $this->handleHeader($http_response_header);
		
		switch ($responseHeader["statusCode"]) {
			case 200: 	$memcache->set($key , array('ETag'=>$responseHeader['ETag'], 'response'=>$response) , NULL , 10);
						break;
			case 304: 	$response=$eTag['response'];
						break;
		}

		$this->debugLog($eTagHeader);
		$this->debugLog($key);
		$this->debugLog($requestUrl);
		$this->debugLog($responseHeader);
		
		return $response;
	}
	
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
	
	private function getMemcache($key) {
	}
	
	private function setMemcache($key) {
	}
	
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