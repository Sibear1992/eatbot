<?php 
	/*
		Беру
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Обработка команды БЕРУ",
			"text" => "Сохраняет заявку на получение продукта",
			"label" => "беру",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		//text
		$geotext = "не отмечено (вы можете уточнить местоположение нажав кнопку ниже)";
		$args = explode(" ", $obj->text);
		
		$oldgeo = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'geo');
		$geoarr = explode(";", $oldgeo);
		if($oldgeo) {
			$geotext = "не отмечено. Установленно последнее заданное местоположение ($geoarr[0], $geoarr[1])";
		}
		if($args[1]) {
			// здесь выполняется поиск совпадений
			$src = share_get_src($pdo, $stat['id'], $args[1]);
			if($src) {
				$cur_user_obj = bot_users_get($stat['group_access_token'], $src['src_id']);
				
				$bt1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$cur_user_obj->domain);
				//$attach = "photo".$self_user_obj->photo_id.",photo".$cur_user_obj->photo_id;
				$attach = "photo".$cur_user_obj->photo_id.",".$src['photo'];
				$text = "{$cur_user_obj->first_name} {$cur_user_obj->last_name}: {$src['info']} (photo: {$src['photo']})";
				// Здесь мы отправляем уведомление тому, кто отдает
				$text2 = "{$self_user_obj->first_name} {$self_user_obj->last_name}: {$obj->text}";
				//$to = "203416927,483780188";
				$btn1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$self_user_obj->domain, '{"cmd":"city"}', "default");
				$linen1 = array($btn1);
				$linesn = array($linen1);
				$kbn = bot_keyboard($linesn, true);
				game_message_sends(
					$stat['group_access_token'], 
					$obj->from_id, 
					$src['src_id'], 
					$text2,
					$kbn,
					"photo".$self_user_obj->photo_id,
					// свои отправляем тому, кто нас нашел
					//$geoarr[1], // если что поменять местами
					//$geoarr[0],
				);
			} else {
				if(!share_dst_add($pdo, $stat['id'], $obj->from_id, $args[1], $obj->text)) {
					$text = "В share_dst_add произошла ошибка";
				} else {
					$text = "&#9989; Заявка на получение продукта создана!\n&#128204; Ключ: $args[1]\n&#128210; Описание: «{$obj->text}»\n&#128465; Для отмены отправьте \"Отмена $args[1]\"".
					"\n&#127759; Местоположение {$geotext}";
					$bt1 = bot_kb_button_location('{"cmd":"loc"}');
				}
			}
		} else {
			$text = "Не указан ключ продукта. Пример использования:\n\nПолучу яблоки любые";
		}
		
		// kb
		$bt2 = bot_kb_button("В меню", '{"cmd":"start"}', "default");
		
		$line1 = array($bt1);
		$line2 = array($bt2);
		$lines = array($line1, $line2);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
			"attachment" => $attach,
			//"lat" => "55.673421", //$geoarr[0],
			//"long" => "37.780887", //$geoarr[1],
		);
	};