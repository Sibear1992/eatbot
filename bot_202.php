<?php 
	/*
		loc
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Локация",
			"text" => "Задание локации",
			"label" => "loc",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// in 
		$gtext = "Координаты отсутствуют";
		if($obj->geo) {
			$lat = $obj->geo->coordinates->latitude;
			$long = $obj->geo->coordinates->longitude;
			$country = $obj->geo->place->country;
			$city = $obj->geo->place->city;
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'geo', "$lat;$long");
			$gtext = "&#9989; Спасибо, Ваше местоположение обновлено!\n&#127759; Страна: $country\n&#127960; Город: $city";
		}
		
		// out
		//text
		$text = $gtext;
		
		// kb
		$bt1 = bot_kb_button("В Меню", '{"cmd":"start"}', "default");
		$bt2 = bot_kb_button("Тест", '{"cmd":"test"}', "default");
		
		$line1 = array($bt1, $bt2);
		$lines = array($line1);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};