<?php 
	/*
		start
		
		Выполняется по команде start
		Отправляет основной интерфейс игрового процесса со всеми данными пользователя и основными кнопками
		кнопки: home, wc, pass, city, coins
	*/

	$about = function() {
		return array(
			"title" => "Скрипт номер один",
			"text" => "Будьте здоровы",
			"label" => "start",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		
		// Если первый раз в игре
		if(!game_get_status($pdo, $obj->from_id)) {
			game_set_status($pdo, $obj->from_id, 1);
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'people', 0);
		}
		
		
		// init data
		$advice = "сидите дома.";
		
		// get data
		$user_obj = bot_users_get($stat['group_access_token'], $obj->from_id);
		// grech
		$grech = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'grech');
		if(!$grech) {
			$grech = 0;
		}
		$papper = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'papper');
		if(!$papper) {
			$papper = 0;
		}
		$people = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'people');
		if(!$people) {
			$people = 0;
		}
		$fine = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'fine');
		if(!$fine) {
			$fine = 0;
		}
		$pech = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'pech');
		if(!$pech) {
			$pech = 0;
		}
		// coins 
		$coins = game_get_score($pdo, $obj->from_id);
		if(!$coins) {
			$coins = 0;
		}
		
		//kb
		$bt1 = bot_kb_button("&#127969; Я дома", '{"cmd":"home"}', "positive");
		$bt2 = bot_kb_button("&#128701; Новости", '{"cmd":"wc"}', "primary");
		$bt3 = bot_kb_button("&#127915; Пропуск", '{"cmd":"pass"}', "default");
		$bt4 = bot_kb_button("&#128640; На выход!", '{"cmd":"city"}', "negative");
		$bt5 = bot_kb_button("&#128176; У вас ".$coins." коинов", '{"cmd":"coins"}', "default");
		// location TODO
		/* $bt_loc = bot_kb_button_location('{"cmd":"loc"}'); */
		
		// logic
		if($grech > 0) {
			$advice = "поеште.";
			$bt1 = bot_kb_button("&#127835; Кушать", '{"cmd":"eat"}', "positive");
		}
		
		$passid = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'passid');
		$tm2 = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'passtime');
		$passdate = date("H:i", $tm2);
		$tm = $tm2 - time();
		if($tm > 0) {
			$tm -= (3 * 60 * 60);
			$passtext = "Ваш игровой пропуск {$passid} действует до {$passdate}.";
			$lbl = date("H:i", $tm);
			$bt3 = bot_kb_button("&#9203; {$lbl}", '{"cmd":"pass"}', "default");
			if(game_get_status($pdo, $obj->from_id) == 2) {
				$bt4 = bot_kb_button("&#127969; Домой", '{"cmd":"home"}', "positive");
			} else {
				$bt4 = bot_kb_button("&#128640; На улицу", '{"cmd":"city"}', "positive");
			}
			
		} else {
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'passid', '');
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'passtime', 0);
			$passtext = "тут ваши запасы:";
		}
		
		$text = "{$user_obj->first_name}, {$passtext}\n".
			"\n&#127835; Запас гречки: ".$grech." кг.".
			"\n&#128169; Запас бумаги: ".$papper." рул.".
			"\n&#129313; Контактёры: ".$people." чел.".
			"\n&#128110; Штрафы: ".$fine."р.".
			"\n&#128130; Печенеги: ".$pech." печ.".
			"\n\n&#127891; Совет: ".$advice;
		
		$line1 = array($bt1, $bt2);
		$line2 = array($bt3, $bt4);
		$line3 = array($bt5);
		/* $line4 = array($bt_loc); */
		
		$lines = array($line1, $line2, $line3);
		/* $lines = array($line1, $line2, $line3, $line4); */
		
		$kb = bot_keyboard($lines, true);
		
		
		// send
		return array(
			"text" => $text,
			"keyboard" => $kb,
			//"attachment" => "photo-194208312_457239024",
			"template" => $carousel,
		);
	};