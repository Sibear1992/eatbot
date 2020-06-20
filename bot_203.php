<?php 
	/*
		Отдаю
		
		Описание
	*/
	$about = function() {
		return array(
			"title" => "Обработка команды ОТДАЮ",
			"text" => "Сохраняет продукт в базе для передачи на запросы",
			"label" => "отдаю",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		//text
		$attach = "";
		$geotext = "Bы можете уточнить местоположение нажав кнопку ниже";
		$args = explode(" ", $obj->text);
		
		$oldgeo = bot_vars_get($pdo, $stat['id'], $obj->from_id, 'geo');
		$geoarr = explode(";", $oldgeo);
		if($oldgeo) {
			$geotext = "Установленно последнее заданное местоположение ($geoarr[0], $geoarr[1])";
		}
		if($args[1]) {
			// Если отдается что-то из имеющихся на получение
			$dst = share_get_dst($pdo, $stat['id'], $args[1]);
			$photo = false;
			if($obj->attachments[0]->photo->id) {
				$photo = "photo{$obj->attachments[0]->photo->owner_id}_{$obj->attachments[0]->photo->id}";
			}
			if($dst) {
				$self_user_obj = bot_users_get($stat['group_access_token'], $obj->from_id);
				$cur_user_obj = bot_users_get($stat['group_access_token'], $dst['dst_id']);
				// Мы нашли кому надо (dst) теперь ему отправляем инфу о том, кто отдает (src) т.е текущий польз.
				$dstgeo = bot_vars_get($pdo, $stat['id'], $dst['dst_id'], 'geo');
				$dstgeoarr = explode(";", $dstgeo);
				// это отправляем себе - тот кого нашли
				$bt1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$cur_user_obj->domain);
				$attach = "photo".$cur_user_obj->photo_id.",".$photo;
				$text = "{$cur_user_obj->first_name} {$cur_user_obj->last_name}: {$dst['info']}";
				//$text = $text."\n\nattach: photo{$obj->attachments[0]->photo->owner_id}_{$obj->attachments[0]->photo->id}";
				// Отправление сообщения юзеру dst тому, кто берет
				$text2 = "{$self_user_obj->first_name} {$self_user_obj->last_name}: {$obj->text}";
				//$to = "203416927,483780188";
				$btn1 = bot_kb_button_link("Смотреть профиль", "https://vk.com/".$self_user_obj->domain, '{"cmd":"city"}', "default");
				$linen1 = array($btn1);
				$linesn = array($linen1);
				$kbn = bot_keyboard($linesn, true);
				game_message_sends(
					$stat['group_access_token'], 
					$obj->from_id, 
					$dst['dst_id'], 
					$text2,
					$kbn,
					"photo".$self_user_obj->photo_id,
					// свои отправляем тому, кто нас нашел
					$geoarr[1], // если что поменять местами
					$geoarr[0],
				);
			} else {
				if(!share_src_add($pdo, $stat['id'], $obj->from_id, $args[1], $obj->text, $photo)) {
					$text = "В share_src_add произошла ошибка";
				} else {
					$bt1 = bot_kb_button_location('{"cmd":"loc"}');
					$text = "&#9989; Запрос на передачу продукта создан!\n&#128204; Ключ: $args[1]\n&#128210; Описание: «{$obj->text}»\n&#128465; Для отмены отправьте \"Отмена $args[1]\"".
					"\n&#127759; {$geotext}";
					if($photo) {
						$text = $text."\n(+ {$photo})";
					}
				}
			}
		} else {
			$text = "Не указан ключ продукта. Пример использования:\n\nОтдаю яблоки сорта белый налив 3 кг";
		}
		
		// kb
		//$bt1 = bot_kb_button_location('{"cmd":"loc"}');
		$bt2 = bot_kb_button("В меню", '{"cmd":"start"}', "default");
		
		$line1 = array($bt1);
		$line2 = array($bt2);
		$lines = array($line1, $line2);
		
		$kb = bot_keyboard($lines, true);
		
		return array(
			"text" => $text,
			"keyboard" => $kb,
			"attachment" => $attach,
			"lat" => $dstgeoarr[0],
			"long" => $dstgeoarr[1],
		);
	};