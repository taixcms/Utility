<?php

namespace phputilities;
/**
 * Created by PhpStorm.
 * User: pg
 * Date: 10.09.2018
 * Time: 15:21
 */
class digitalsamba {
	const API_EndPoint = 'http://cbrates.rbc.ru/tsv/';
	const Authorization = 'YWRtaW46c2FtYmEyMDAy';

	public function __construct() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{{API_EndPoint}}/api/2/{{user_name}}/authverify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS =>"input_type=json&rest_data={\n  \"id\":\"1\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic YWRtaW46c2FtYmEyMDAy"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
	}

	public function auth() {

	}

	private function parseUA() {

	}

}