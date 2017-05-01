<?php
/**
 * The ECB Foreign Exchange Rates Reference for PHP5.
 * This class obtains the current & historical Foreign Exchange Rates from the ECB.
 * The source XML is updated daily around 16:00 CET, Central European Standard Time.
 * Please consider a donation, while you use this class in your project.
 * @copyright Copyright 2017 by Martin Zeitler, All rights reserved.
 * @author https://plus.google.com/+MartinZeitler
 * @bitcoin 19uySyXrtqQ71PFZWHb2PxBwtNitg2Dp6b
**/
class PhpOptionsException{

	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}

class fxreference {

	private $sourceUrl = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
	private $xmlContext = "";
	private $data = array();
	private $debug = FALSE;

	/**
	 * PHP5 Constructor
	**/
	public function __construct() {
		try {
			if(ini_get('allow_url_fopen')) {
				$this->parseXml();
			} else {
				throw new PhpOptionsException('allow_url_fopen is OFF');
			}
		} catch(PhpOptionsException $e) {
			die($e->toString());
		}
	}

	/**
	 * The parsed values are always relative to 1,00 €.
	 * @return void
	**/
	private function parseXml() {
		$this->xmlContext = file($this->sourceUrl);
		foreach($this->xmlContext as $line) {
			if(preg_match("/currency='([[:alpha:]]+)'/",$line,$currencyCode)){
				if(preg_match("/rate='([[:graph:]]+)'/",$line,$rate)){
					if($this->debug) {echo "1&euro;=".$rate[1]." ".$currencyCode[1]."<br/>";}
					$this->data[$currencyCode[1]] = $rate[1];
				}
			}
		}
	}

	/** @return the parsed data as a JSON Array. */
	public function getJson() {
		return json_encode($this->data);
	}

	/** @return the parsed data as a PHP Array. */
	public function getArray() {
		return $this->data;
	}
}
?>
