<?php 
	/*
		eat
		
		При еде расходуется киллограмм гречки и пользователю предлагается
		поспать - start
		пойти за гречкой - city
	*/
	$about = function() {
		return array(
			"title" => "Скрипт mask systems",
			"text" => "Будьте здоровы",
			"label" => "eat",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		
		$grech = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'grech');
		if($grech < 1) {
			$text = "Гречки больше нет";
			$bt1 = bot_kb_button("Поспать", '{"cmd":"start"}', "default");
			$bt2 = bot_kb_button("Пойти купить", '{"cmd":"city"}', "default");
		} else {
			$grech--;
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'grech', $grech);
			
			$text = "Приятного кушания!".
			"\n\nBы съели килограм гречки.";
			$bt1 = bot_kb_button("Поспать", '{"cmd":"start"}', "default");
			$bt2 = bot_kb_button("Пойти гулять", '{"cmd":"city"}', "default");
		}
		
		$line1 = array($bt1, $bt2);
		$lines = array($line1);
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};