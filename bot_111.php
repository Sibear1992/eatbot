<?php 
	/*
	
		home 
		Выполняется по нажатию кнопки Я дома/домой
		Проверяет есть ли дома гречка и предлагает поесть, если гречки нет то ее приносят волонтеры с бумагой
		кнопки на - start
	*/
	$about = function() {
		return array(
			"title" => "Скрипт mask systems",
			"text" => "Будьте здоровы",
			"label" => "home",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// Установка статуса Я ДОМА
		game_set_status($pdo, $obj->from_id, 1);
		
		$bt2 = bot_kb_button("&#128688; Помыть руки", '{"cmd":"start"}', "positive");
		$grech = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'grech');
		if(!$grech) {
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'grech', 2);
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'papper', 1);
			$text = "&#128008; Мяу...\nОго! Волонтеры принесли вам 2 кг гречеи и туалетную бумагу. Поздравляем!";
			$bt1 = bot_kb_button("Забрать", '{"cmd":"start"}', "default");
		} else {
			$text = "&#128008; Мяу ррр...мяу...\nВаш кот Вас прекрасно понимает, не то что некоторые...";
			$bt1 = bot_kb_button("&#128008; Погладить кота", '{"cmd":"start"}', "default");
		}
		
		$line1 = array($bt1);
		$line2 = array($bt2);
		$lines = array($line1, $line2);
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};