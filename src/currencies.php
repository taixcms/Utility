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
	private $date = 0;

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