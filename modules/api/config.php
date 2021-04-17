<?php

function json_error($message, $params = []){
	return json_encode(array_merge([
		'error' => true,
		'message' => $message,
	], $params), JSON_UNESCAPED_UNICODE);
}

function json_success($message, $params = []){
	return json_encode(array_merge([
		'success' => true,
		'message' => $message,
	], $params), JSON_UNESCAPED_UNICODE);
}
