<?php 
	/*
		wc
		В туалете пользователь узнает о числе зараженных вирусом и расходует рулон бумаги
		кнопка на - start
	*/

	$about = function() {
		return array(
			"title" => "Скрипт mask systems",
			"text" => "Будьте здоровы",
			"label" => "wc",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		
		$papper = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'papper');
		if($papper < 1) {
			$text = "Закончилась туалетная бумага";
			$bt1 = bot_kb_button("Пойти купить", '{"cmd":"city"}', "default");
		} else {
			
			$url = "https://coronavirus-tracker-api.herokuapp.com/v2/locations?source=jhu&country_code=RU";
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_VERBOSE, true);
			$resp = json_decode(curl_exec($ch));
			curl_close($ch);
			
			$papper--;
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'papper', $papper);
			
			$text = "Свежая пресса.\nПо данным Coronavirus-Tracker-API в России ".$resp->latest->confirmed." заболевших COVID-19!".
			"\n\nБыл использован один рулон туалетной бумаги.";
			$bt1 = bot_kb_button("Покинуть туалет", '{"cmd":"start"}', "default");
		}
		
		$bt2 = bot_kb_button("Главная", '{"cmd":"start"}', "default");
		
		$line1 = array($bt1);
		$line2 = array($bt2);
		$lines = array($line1, $line2);
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};