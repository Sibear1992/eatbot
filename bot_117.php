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
		
		
		// out
		//text
		$text = "Сервис локации позволяет находить прохожих поблизости.\n".
		"Пока что он отключен, как только появится много пользователей мы его активируем,";
		
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