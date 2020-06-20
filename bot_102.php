<?php	
	$about = function() {
		return array(
			"title" => "Говорит погоду",
			"text" => "Пришлите боту свои координаты и получите погоду",
			"label" => "погода",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// geo
		$lat = $obj->geo->coordinates->latitude;
		$long = $obj->geo->coordinates->longitude;
		$country = $obj->geo->place->country;
		$city = $obj->geo->place->city;
		
		// request to API
		$headers = array(
			"X-Yandex-API-Key: 747e0d42-fead-4a1b-8ea6-ad28e3235763",
			//"Content-Type: application/json"
		);
		
		$params = http_build_query(array(
			"lat" => $lat,
			"lon" => $long,
			"limit" => 2,
			"hours" => false,
		));
		
		$url = "https://api.weather.yandex.ru/v1/forecast?".$params;
		// «Погода на вашем сайте» — https://api.weather.yandex.ru/v1/informers/
		// $url = https://api.weather.yandex.ru/v1/informers?lat=55.75396&lon=37.620393
		$ch = curl_init(); 
		/*
		echo '<pre>';
		print_r(curl_getinfo($ch));
		echo '</pre>';
		*/
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_VERBOSE, true);
		$resp = json_decode(curl_exec($ch));
		curl_close($ch);
		
		switch($resp->fact->prec_type) {
			case 0:
				$prec = 'Без осадков';
				break;
			case 1:
				$prec = 'Дождь';
				break;
			case 2:
				$prec = 'Дождь со снегом';
			case 3:
				$prec = 'Снег';
				break;
		}
		
		$obs_time = date('H:i:s', $resp->fact->obs_time);
		
		// send
		return array(
			"text" => "&#127746; ".$city.", ".$country."\n\n".
			"Сейчас (Замер в: ".$obs_time.")\n".
			$resp->fact->temp." градусов\n".
			"Ощущается как ".$resp->fact->feels_like."\n".
			"Ветер ".$resp->fact->wind_speed." м/c\n".
			$prec."\n\n".
			"lat: ".$lat." long: ".$long,
		);
	};