<?php

namespace phputilities;
/**
 * Created by PhpStorm.
 * User: pg
 * Date: 10.09.2018
 * Time: 15:21
 */
class currencies {
	const url = 'http://cbrates.rbc.ru/tsv/';
	const file = '.tsv';
	const fileUA = '&json';
	const urlUA = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=';
	private $date = 0;
	private $currency_code = 0;
	private $CCA = ['036' => 'AUD', '124' => 'CAD', '156' => 'CNY', '203' => 'CZK', '208' => 'DKK', '344' => 'HKD', '348' => 'HUF', '356' => 'INR',
'368' => 'IQD', '364' => 'IRR', '392' => 'JPY', '398' => 'KZT', '484' => 'MXN', '532' => 'ANG', '554' => 'NZD', '578' => 'NOK',
'643' => 'RUB', '702' => 'SGD', '710' => 'ZAR', '752' => 'SEK', '756' => 'CHF', '818' => 'EGP', '826' => 'GBP', '840' => 'USD',
'860' => 'UZS', '944' => 'AZN', '974' => 'BYR', '975' => 'BGN', '978' => 'EUR', '980' => 'UAH', '985' => 'PLN', '986' => 'BRL'
];

	public function __construct($date = null) {
		if ($date === null) {
			$date = time();
		}
		$this->date = $date;
	}

	public function curs($currency_code) {
		$url = self::url;
		$curs = 0;
		try {
			if (!is_numeric($currency_code)) {
				throw new \Exception('Invalid currency code sent');
			}
			$url .= $currency_code . '/';
			if ($this->date <= 0) {
				throw new \Exception('Invalid date sent');
			}
			$url .= date('Y/m/d', $this->date);
			$url .= self::file;
			$page = @file_get_contents($url);
			if ($page) {
				$curs = $this->parse($page);
			}

		} catch (\Exception $e) {
			echo 'Could not get the exchange rate. ', $e->getMessage();
		}
		return $curs;
	}

	public function cursUA($currency_code) {
		$this->currency_code = $currency_code;
		$url = self::urlUA;
		$curs = 0;
		try {
			if (!is_numeric($currency_code)) {
				throw new \Exception('Invalid currency code sent');
			}
			$url .= $this->CCA[$currency_code] . '&date=';
			if ($this->date <= 0) {
				throw new \Exception('Invalid date sent');
			}
			$url .= date('Ymd', $this->date);
			$url .= self::fileUA;
			$page = @file_get_contents($url);
			if ($page) {
				$curs = $this->parseUA($page);
			}

		} catch (\Exception $e) {
			echo 'Could not get the exchange rate. ', $e->getMessage();
		}
		return $curs;
	}

	private function parseUA($file) {
		if (empty($file)) {
			throw new \Exception('An incorrect currency code may have been specified, it is also possible that the exchange rate has not yet been set for the specified date, or the server "bank.gov.ua" is not available.');
		}
		if ($this->currency_code == '980') {
			return 1;
		}
		if (!empty(json_decode($file,true)[0]['rate'])) {
			return json_decode($file,true)[0]['rate'];
		} else {
			throw new \Exception('The server did not issue results for this currency on the expiring date');
		}
	}


	private function parse($file) {
		if (empty($file)) {
			throw new \Exception('An incorrect currency code may have been specified, it is also possible that the exchange rate has not yet been set for the specified date, or the server "cbrates.rbc.ru" is not available.');
		}
		$curs = explode("\t", $file);
		if (!empty($curs[1])) {
			return $curs[1];
		} else {
			throw new \Exception('The server did not issue results for this currency on the expiring date');
		}
	}
}