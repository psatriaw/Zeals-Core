<?php

namespace App\Http\Controllers\Helpers;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class GoogleAnalyticsHelper extends Controller {
	public function sendEvent(string $event_name, array $params): void {
		$data = array(
			'api_secret' => 'o5LOy7t_Q9WPXywtrjukqw',
			'measurement_id' => 'G-0SVEZW47K8',
			'client_id' => Uuid::uuid4(), // generates a random id
			'events' => array(
				'name' => $event_name,
				'params' => $params,
			)
		);

		$url = 'https://www.google-analytics.com/mp/collect';
		$content = http_build_query($data);
		$content = utf8_encode($content);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/x-www-form-urlencoded'));
		curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
		curl_setopt($ch,CURLOPT_POST, TRUE);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec($ch);
		curl_close($ch);
	}
}