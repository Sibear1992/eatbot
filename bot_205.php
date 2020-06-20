<?php 
	/*
		Отмена
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Обработка команды ОТМЕНА",
			"text" => "Отменяет заявку",
			"label" => "отмена",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		$args = explode(" ", $obj->text);
		// todo обработка пустого ключа
		if($args[1]) {
			if(!share_set_status_name($pdo, $stat['id'], $obj->from_id, $args[1], 0)) {
				$text = "В share_set_status_name произошла ошибка";
			} else {
				$text = "Запрос на отмену ключа \"{$args[1]}\" исполнен.";
			}
		} else {
			$text = "Не указан ключ продукта. Пример использования:\n\nОтмена яблоки";
		}
		
		// kb
		$bt1 = bot_kb_button("В меню", '{"cmd":"start"}', "default");
		$line1 = array($bt1);
		$lines = array($line1);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};