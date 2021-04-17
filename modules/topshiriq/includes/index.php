<?php
declare(strict_types=1);
require_once 'config.php';
$topshiriq = $connection->table('topshiriq')->get();

foreach ($topshiriq as $item) {
    $status = status($item->status);
    $data[] = [
        'id'        => $item->id,
        'fio'       => $item->name,
        'money'     => $item->money,
        'saldo'     => $item->saldo,
        'saldo'     => $item->saldo,
        'status'    => $status['status'],
        'alert'      => $status['alert'],
        'phone'     => $item->phone,
    ];
}

echo $view->render(
    'topshiriq::index',[
    'title'      => 'Malumotlar',
    'data' => $data
    ]);