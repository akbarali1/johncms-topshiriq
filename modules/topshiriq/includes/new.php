<?php
declare(strict_types=1);
if ($request->getMethod() === 'POST'){

    $fio = $request->getPost('fio', '', FILTER_SANITIZE_STRING);
    $money = $request->getPost('money', '', FILTER_SANITIZE_STRING);
    $phone = $request->getPost('phone', '', FILTER_SANITIZE_STRING);

    $cid = $connection->table('topshiriq')->insertGetId([
        'name'      => $fio,
        'phone'     => $phone,
        'money'     => $money,
        'create_ad' => $user->id,
        'create_time' => date('Y-m-d', time()),
        'saldo'     => '',
        'status' => '1'
        ]);
    header('Location: '.$config['homeurl']."/topshiriq/view/".$cid."/");
    die;
}
$data = [
    'name' => '',
    'money' => '',
    'phone' => ''
];

echo $view->render(
    'topshiriq::new',[
    'title'      => "Yangi malumot qo`shish",
    'data' => $data,
    'type' => 'new'
    ]);
