<?php
declare(strict_types=1);
require_once 'config.php';

$topshiriq = $connection->table('topshiriq')->where('id', $id)->first();

$status = status($topshiriq->status);

echo $view->render(
    'topshiriq::view',[
    'title'      => $topshiriq->name." ma'lumotlarini ko`rish",
    'data' => $topshiriq,
    'status' => $status
    ]);
