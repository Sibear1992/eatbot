<?php 
	/*
		start
		
		Главная страница фудшеринг-бота
	*/
	$about = function() {
		return array(
			"title" => "Главная (v.1)",
			"text" => "Отвечает на команду start",
			"label" => "start",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		//Получаем статус
		$status = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'status');
		if($status) {
			$text = "&#128221; Ваши заявки:\n\n";
			$togive = share_src_fetch($pdo, $stat['id'], $obj->from_id);
			if($togive) {
				$text = $text."Список передаваемых продуктов:\n";
				foreach($togive as $item) {
					$text = $text."&#127823; ".$item['name']."\n";
				}
			} else {
				$text = $text."&#127823; Нет заявок на передачу.\n";
			}
			$toget = share_dst_fetch($pdo, $stat['id'], $obj->from_id);
			if($toget) {
				$text = $text."\nСписок нужных продуктов:\n";
				foreach($toget as $item) {
					$text = $text."&#128230; ".$item['name']."\n";
				}
			} else {
				$text = $text."\n&#128230; Нет заявок на получение.\n";
			}
			$score = game_get_score($pdo, $obj->from_id);
			$text = $text."\nЗа успешно переданные продукты Вам начисляются очки.\n\n".
			"Количество очков: ".$score." &#10004;";
		} else {
			$text = "Теперь, когда мы с Вами познакомились, мы Вам расскажем, о нашем сервисе есть команды.\n\n".
			"Этот сервис создан в рамках проекта 1 МЛН ТОНН для безвозмездной передачи еды по принципу P2P.\n".
			"Сервиса позволяет узнать о продуктах, которые готовы безвозмездно отдать другие пользователи.\n\n".
			"Вы также можете стать благотворителем и сообщать системе о продуктах, которые вы готовы передать.\n".
			"Все обновления подходящие для Вас будут приходить мгновенно.";
			
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'status', "1");
		}
		
		// kb
		//$bt1 = bot_kb_button("Старт", '{"cmd":"start"}', "default");
		//$bt2 = bot_kb_button("Тест", '{"cmd":"test"}', "default");
		
		$bt_loc = bot_kb_button_location('{"cmd":"loc"}');
		$bt1 = bot_kb_button("В меню", '{"cmd":"start"}', "primary");
		$bt2 = bot_kb_button("Справочник", '{"cmd":"help"}', "default");
		
		$line1 = array($bt_loc);
		$line2 = array($bt1, $bt2);
		$lines = array($line1, $line2);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
		);
	};