<?php 

	$about = function() {
		return array(
			"title" => "Случайная цитата",
			"text" => "Отправляет в ответ случайную цитату",
			"label" => "цитата",
		);
	};

	$bot = function($pdo, $stat, $obj) {
		// data array
		$quotes = array(
			"Сначала было хорошо, потом очень хорошо, а потом так хорошо, что до сих пор плохо.",
			"Друзья вКонтакте — как шариковые ручки, 120 штук и только несколько пишут.",
			"И самая широкая кровать тесна для двоих, если один из этих двоих — кот!",
			"Если человек на 80% состоит из воды, то без мечты и желаний он всего лишь вертикальная лужа.",
			"Если это не весело, значит вы делаете это неправильно.\n\nБоб Бассо",
			"Девушка даже не задумывается, чего ей не хватает, пока подруга не похвастается.",
			"Женатый мужчина – как кот ученый: «Идет налево – песнь заводит, направо – сказку говорит».",
			"Женщина верит, что дважды два будет пять, если хорошенько поплакать и устроить скандал.\nnДжордж Элиот",
			"Занятие ерундой на рабочем месте развивает боковое зрение, слух и бдительность в целом",
			"Отольются кошке мышкины слёзки цианидом в ложке.",
			"Если стараться обходить все неприятности, то можно пройти мимо всех удовольствий!",
			"- Жить, как говорится, хорошо…\n- А хорошо жить еще лучше.\n- Точно!\n\nКавказская пленница",
			"Лето — это время года, когда очень жарко, чтобы заниматься вещами, которыми заниматься зимой было очень холодно\n\nМарк Твен",
		);
		// send
		return array(
			"text" => $quotes[rand(0, count($quotes)-1)],
		);
	};