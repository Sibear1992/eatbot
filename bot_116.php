<?php 
	/*
		coins
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Коины",
			"text" => "Кошелек и Магазин",
			"label" => "coins",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// in 
		
		
		// out
		//text
		
		$orders = 0;
		$text = "Магазин. {$orders} предложений.\n".
			"Здесь Вы сможете обменивать коины на скидки от партнеров проекта.";
		
		// kb
		$bt1 = bot_kb_button("На главную", '{"cmd":"start"}', "default");
		$bt2 = bot_kb_button("Гулять", '{"cmd":"city"}', "default");
		
		$line1 = array($bt1, $bt2);
		$lines = array($line1);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};