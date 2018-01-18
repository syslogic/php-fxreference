<?php
/**
 * 
 * This class obtains the current & historical Foreign Exchange Rates from the ECB Web-Server.
 * The source XML is updated daily around 16:00 CET, Central European Standard Time.
 * I'm a freelance developer and in no way affiliated with the ECB System.
 * For the copyright of the financial information, which is being provided,
 * @see https://www.ecb.europa.eu/home/disclaimer/html/index.en.html
 * @copyright Copyright 2017 by Martin Zeitler, All rights reserved.
 * @author https://plus.google.com/+MartinZeitler
 * @bitcoin 19uySyXrtqQ71PFZWHb2PxBwtNitg2Dp6b
**/
class fxreference {

	/* the base URL */
	private $baseUrl = "http://www.ecb.europa.eu/";

	/* XML related */
	private $xmlSourceDaily = "stats/eurofxref/eurofxref-daily.xml";

	/* RSS related */
	private $rssSource = "rss/fxref-SYMBOL.html";

	/* available symbols */
	private $symbols = array(
            'usd', 'jpy', 'bgn', 'czk', 'dkk', 'eek', 'gbp', 'huf', 'pln', 'ron', 'sek', 'chf',
            'nok', 'hrk', 'rub', 'try', 'aud', 'brl', 'cad', 'cny', 'hkd', 'idr', 'inr', 'krw',
            'mxn', 'myr', 'nzd', 'php', 'sgd', 'thb', 'zar'
	);

	private $debug = FALSE;
	private $symbol = null;
	private $data = array();
	private $xmlFeed = "";

	/**
	 * PHP5 Constructor
	**/
	public function __construct($currency_code = null) {

            /* while a currency-code was provided: */
            if(! is_null($currency_code)) {

                /* parse the historical RSS for the symbol: */
                if(! function_exists('simplexml_load_string')) {
                    die("fxreference fatal error: PHP simplexml extension is not loaded.");
                } else {
                    $this->symbol = trim(strtolower($currency_code));
                    $this->parseRss();
                }

            } else {

                /*  else, parse the daily XML: */
                if(! ini_get('allow_url_fopen')) {
                    die("fxreference fatal error: PHP option allow_url_fopen is OFF");
                } else {
                    $this->parseDailyXml();
                }
            }
	}

	/**
	 * the parsed values are always relative to 1,00 €.
	 * @return void
	**/
	private function parseDailyXml() {
            $this->xmlFeed = file($this->baseUrl.$this->xmlSourceDaily);
            foreach($this->xmlFeed as $line) {
                if(preg_match("/currency='([[:alpha:]]+)'/",$line, $currencyCode)) {
                    if(preg_match("/rate='([[:graph:]]+)'/",$line, $exchangeRate)) {
                        $currencyCode = $currencyCode[1];
                        $exchangeRate = $exchangeRate[1];
                        if($this->debug) {echo "1&euro; = ".$exchangeRate." ".$currencyCode."<br/>";}
                        $this->data[strtolower($currencyCode)] = $exchangeRate;
                    }
                }
            }
	}

	/** @return the parsed data as a JSON Array. */
	private function parseRss(){
            if(!is_null($this->symbol) && in_array($this->symbol, $this->symbols)) {
                $this->rssSource = $this->baseUrl.str_replace("SYMBOL", $this->symbol, $this->rssSource);
                $this->xmlFeed = implode(file($this->rssSource));
                $xml = simplexml_load_string($this->xmlFeed);
                $json = json_encode($xml);
                $this->data = json_decode($json, TRUE);
	    }
	}

	/** @return the parsed data as a JSON Array. */
	public function toJson() {
		return json_encode($this->data);
	}

	/** @return the parsed data as a PHP Array. */
	public function toArray() {
		return $this->data;
	}
}
?>
