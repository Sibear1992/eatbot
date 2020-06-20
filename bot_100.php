<?php 

	$about = function() {
		return array(
			"title" => "Название скрипта",
			"text" => "Полное описание скрипта для бота",
			"label" => "кот",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// keyboard
		$bt1 = bot_kb_button("&#128573; Кися");
		$bt2 = bot_kb_button("&#128568; Погладить");
		$bt3 = bot_kb_button("&#128571; Покормить", false, "positive");
		$bt4 = bot_kb_button("&#128576; Кинуть тапок", false, "negative");
		$bt5 = bot_kb_button("&#128574; Попросить немного коинов", false, "primary");
		
		$line1 = array($bt1, $bt2);
		$line2 = array($bt3, $bt4);
		$line3 = array($bt5);
		$lines = array($line1, $line2, $line3);
		$kb = bot_keyboard($lines);
		
		//bot_vars_set($pdo, $stat['id'], $obj->from_id, 'test', 'Привет');
		$test_var = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'test');
		
		// send
		return array(
			"text" => "(test_var: $test_var) Мяу Мяу! Мяу Мяу! Мяу Мяу! Мэу!!! Мяу...",
			"keyboard" => $kb,
			"attachment" => "photo-154255986_456239023",
		);
	};