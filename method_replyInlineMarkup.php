<?php
//Метод, который отправляет сообщение с клавиатурой
const TOKEN = 'TOKEN';
const BASE_URL = 'https://api.telegram.org/bot';

function sendRequest(string $method, array $params = []) : array {
	if(!empty($params))
		$url = BASE_URL . TOKEN . '/' . $method . '?' . http_build_query($params);
	else
		$url = BASE_URL . TOKEN . '/' . $method;
	return json_decode(file_get_contents($url), JSON_OBJECT_AS_ARRAY);
}
function generateKeyboard(string $numberquestion, string $callback_data = null) : array{
	return ['inline_keyboard' => [
			[
				['text' => 'Ответ 1', 'callback_data' => ($callback_data . "\n" . "Ответ 1" . " - вопрос " . $numberquestion)],//В новую клавиатуру, по-идее, данные callback предыдущей. Почему выводит только, к примеру, 'Ответ 1 - вопрос 2'
				['text' => 'Ответ 2', 'callback_data' => ($callback_data . "\n" . "Ответ 2" . " - вопрос " . $numberquestion)], //Первый ряд кнопок
			],[
				['text' => 'Ответ 3', 'callback_data' => ($callback_data . "\n" . "Ответ 3" . " - вопрос " . $numberquestion)],
				['text' => 'Ответ 4', 'callback_data' => ($callback_data . "\n" . "Ответ 4" . " - вопрос " . $numberquestion)], //Второй ряд кнопок
			]
		]
	];
}
/*function generateKeyboard(string $numberquestion, string $callback_data = null) : array{
	return ['inline_keyboard' => [
			[
				['text' => 'Ответ 1', 'callback_data' => $numberquestion],
				['text' => 'Ответ 2', 'callback_data' => $numberquestion], //Первый ряд кнопок
			],[
				['text' => 'Ответ 3', 'callback_data' => $numberquestion],
				['text' => 'Ответ 4', 'callback_data' => $numberquestion], //Второй ряд кнопок
			]
		]
	];
}*/




//Получаем обновление
$update = json_decode(file_get_contents('php://input'),JSON_OBJECT_AS_ARRAY);

if(isset($update['callback_query'])){
	/*sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>"Ответ: " . $update["callback_query"]["data"]
			]);*/
	switch($update['callback_query']['data'][-1]){
			case '1':
			
				$fd = fopen('result.txt','a+');
				fwrite($fd, $update['callback_query']['data']);
				fclose($fd);
				//редактируем сообщение
				sendRequest('editMessageText', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'message_id'=>$update["callback_query"]["message"]["message_id"],
				'text'=>'Вопрос 2',
				'reply_markup'=>json_encode(generateKeyboard('2',['callback_query']['data']))
				]);
				
				
				//Метод telegramAPI answerCallbackQuery
				/*sendRequest('answerCallbackQuery',[
					'callback_query_id'=>$update['callback_query']["id"],
					'text'=>$update['callback_query']['data']
				]);*/
				
				
				//Выводим новую клавиатуру
				/*sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 2',
				//'reply_markup'=>json_encode(generateKeyboard('2'))
				'reply_markup'=>json_encode(generateKeyboard('2',['callback_query']['data']))
				]);*/
				break;
			case '2':
				$fd = fopen('result.txt','a+');
				fwrite($fd, $update['callback_query']['data']);
				fclose($fd);
				
				sendRequest('editMessageText', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'message_id'=>$update["callback_query"]["message"]["message_id"],
				'text'=>'Вопрос 3',
				'reply_markup'=>json_encode(generateKeyboard('3',['callback_query']['data']))
				]);
				
				/*sendRequest('answerCallbackQuery',[
					'callback_query_id'=>$update['callback_query']["id"],
					'text'=>$update['callback_query']['data']
				]);*/
			
				/*sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 3',
				//'reply_markup'=>json_encode(generateKeyboard('3'))
				'reply_markup'=>json_encode(generateKeyboard('3',['callback_query']['data']))
				]);*/
				break;
			case '3':
				$fd = fopen('result.txt','a+');
				fwrite($fd, $update['callback_query']['data']);
				fclose($fd);
				
				sendRequest('editMessageText', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'message_id'=>$update["callback_query"]["message"]["message_id"],
				'text'=>'Вопрос 4',
				'reply_markup'=>json_encode(generateKeyboard('4',['callback_query']['data']))
				]);
			
				/*sendRequest('answerCallbackQuery',[
					'callback_query_id'=>$update['callback_query']["id"],
					'text'=>$update['callback_query']['data']
				]);*/
				
				/*sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 4',
				//'reply_markup'=>json_encode(generateKeyboard('4'))
				'reply_markup'=>json_encode(generateKeyboard('4',['callback_query']['data']))
				]);*/
				break;
			default:
				$fd = fopen('result.txt','a+');
				fwrite($fd, $update['callback_query']['data']);
				fclose($fd);
				$data = file_get_contents('result.txt');
				unlink('result.txt');
				
				sendRequest('editMessageText', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'message_id'=>$update["callback_query"]["message"]["message_id"],
				'text'=>'Итог: ' . $data
				]);
				/*sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Итог: ' . ['callback_query']['data']
				]);*/
			
	}	
	/*switch(end($update['callback_query']['data'])){
		case '1':
			//Отправка клавиатуры
			sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 2',
				'reply_markup'=>json_encode(generateKeyboard('2',$update["callback_query"]["data"]))
			]);
			
			break;
		case '2':
			sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 3',
				'reply_markup'=>json_encode(generateKeyboard('3',$update["callback_query"]["data"]))
			]);
			break;
		case '3':
			sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>'Вопрос 4',
				'reply_markup'=>json_encode(generateKeyboard('4',$update["callback_query"]["data"]))
			]);
			break;
		default:
			sendRequest('sendMessage', [
				'chat_id'=>	$update["callback_query"]["message"]["chat"]["id"],
				'text'=>"Итог:\n" . $update["callback_query"]["data"]
			]);
			break;
	}*/
}
if(isset($update['message']) && $update['message']['text']=='/q' ){
	sendRequest('sendMessage', [
				'chat_id'=>	$update["message"]["chat"]["id"],
				'text'=>'Вопрос 1',
				'reply_markup'=>json_encode(generateKeyboard('1'))
			]);
}
?>