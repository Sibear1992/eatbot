<?php 
	/*
		pass
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Пропуск",
			"text" => "Выдача пропуска",
			"label" => "pass",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// in 
		
		// logic
		$tm2 = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'passtime');
		$tm = $tm2 - time();
		$dt = date("H:i", $tm2); // Fixed 001
		if($tm > 0) {
			$tm -= (3 * 60 * 60);
			$text = "У Вас уже есть действующий пропуск до ".$dt;
			$lbl = "Ещё есть ".date("H:i", $tm);
			
			$bt1 = bot_kb_button("&#127915; ".$lbl, '{"cmd":"city"}', "default");
			$bt2 = bot_kb_button("Главная", '{"cmd":"start"}', "default");
		} else {
			$id = $obj->id;
			$uid = $obj->from_id;
			$rnd = rand(12000, 95990);
			$tm2 = time() + (2 * 60 * 60);
			
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'passid', $uid.'-'.$id.'-'.$rnd);
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'passtime', $tm2);
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'peoples', 5);
			
			$text = "Игровой пропуск действует 2 часа\n\n".
			"ПРОПУСК: ".$uid."-".$id."-".$rnd."\n".
			"Действует до: ".$dt."\n";
			
			// kb
			$bt1 = bot_kb_button("&#128640; На выход!", '{"cmd":"city"}', "positive");
			$bt2 = bot_kb_button("Главная", '{"cmd":"start"}', "default");
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