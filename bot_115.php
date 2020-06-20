<?php 
	/*
		city
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Улица",
			"text" => "Прогулка по улице",
			"label" => "city",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		
		$passid = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'passid');
		$tm2 = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'passtime');
		$tm = $tm2 - time();
		$peoples = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'peoples');
		
		if(($tm > 0) && ($peoples > 0)) {
			$score = game_get_score($pdo, $obj->from_id);
			$score += 2;
			game_set_score($pdo, $obj->from_id, $stat['id'], $score);
			game_set_status($pdo, $obj->from_id, 2);
			$ucnt = game_get_users_count($pdo, $stat['id'], 2);
			$ucnt_all = game_get_users_count_all($pdo, $stat['id']);
			$rand_user = rand(0, $ucnt-1);
			$users = game_get_srv_users($pdo, 725043, 2);
			$cur_user = $users[$rand_user][0];
			
			if($cur_user == $obj->from_id) {
				$cur_user = false;
				$score = game_get_score($pdo, $obj->from_id);
				$score += 10;
				game_set_score($pdo, $obj->from_id, $stat['id'], $score);
			}
			// TODO отправить мессендж тому кого встретили
			/*
			$text = "Тестовое сообщение";
			$to = "203416927,483780188";
			game_message_sends(
				$stat['group_access_token'], 
				$obj->from_id, 
				$to, 
				$text,
				false,
				"photo15686133_457246395"
				
			);
			*/
			// out
			//text
			
			$self_user_obj = bot_users_get($stat['group_access_token'], $obj->from_id);
			$cur_user_obj = bot_users_get($stat['group_access_token'], $cur_user);
			
			if($cur_user) {
				$text = "Сейчас в вашем районе на улице {$ucnt} из {$ucnt_all} чел.".
					"\nВам повстречался прохожий!".
					"\n{$cur_user_obj->first_name} {$cur_user_obj->last_name}";
				$attach = "photo".$self_user_obj->photo_id.",photo".$cur_user_obj->photo_id;
				
				$bt1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$cur_user_obj->domain, '{"cmd":"city"}', "default");
				$bt2 = bot_kb_button("&#127969; Убежать домой", '{"cmd":"home"}', "default");
				$peoples--;
				
				// send
				// Записываем в контактеры
				if($pls = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'people')) {
					$pls++;
					bot_vars_set($pdo, $stat['id'], $obj->from_id, 'people', $pls);
				} else {
					bot_vars_set($pdo, $stat['id'], $obj->from_id, 'people', 1);
				}
				
				$text2 = "Вас встретил {$self_user_obj->first_name} {$self_user_obj->last_name}";
				//$to = "203416927,483780188";
				$btn1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$self_user_obj->domain, '{"cmd":"city"}', "default");
				$linen1 = array($btn1);
				$linesn = array($linen1);
				$kbn = bot_keyboard($linesn, true);
				game_message_sends(
					$stat['group_access_token'], 
					$obj->from_id, 
					$cur_user, 
					$text2,
					$kbn,
					"photo".$self_user_obj->photo_id,
				);
			} else {
				$text = "Сейчас в вашем районе на улице {$ucnt} из {$ucnt_all} чел.".
					"\nПо пути Вам ни кто не встретился, поздравляем!".
					"\nВам начислено 10 коинов.";
				$attach = ""; // todo добавить системное фото
				
				// kb
				$bt1 = bot_kb_button("Гулять дальше", '{"cmd":"city"}', "default");
				$bt2 = bot_kb_button("Пойти домой", '{"cmd":"home"}', "default");
				$peoples--;
			}
			bot_vars_set($pdo, $stat['id'], $obj->from_id, 'peoples', $peoples);
			$text .= "\nДоступно поисков прохожего: {$peoples}.";
		} else {
			$text = "Пропуск всё";
			$bt1 = bot_kb_button("Страдать", '{"cmd":"home"}', "default");
			$bt2 = bot_kb_button("Пойти домой", '{"cmd":"home"}', "default");
		}
		
		$line1 = array($bt1);
		$line2 = array($bt2);
		
		$lines = array($line1, $line2);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"attachment" => $attach,
			"keyboard" => $kb,
		);
	};