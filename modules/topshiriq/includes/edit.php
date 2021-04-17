<?php
declare(strict_types=1);

$topshiriq = $connection->table('topshiriq')->where('id', $id)->first();

if ($request->getMethod() === 'POST'){

    $fio = $request->getPost('fio', '', FILTER_SANITIZE_STRING);
    $money = $request->getPost('money', '', FILTER_SANITIZE_STRING);
    $phone = $request->getPost('phone', '', FILTER_SANITIZE_STRING);

$connection->table('topshiriq')->where('id', '=', $id)
->update(
    [
        'name'      => $fio,
        'phone'     => $phone,
        'money'     => $money,
        ]
    );
    header('Location: '.$config['homeurl']."/topshiriq/view/".$id."/");
    die;
}

$data = [
    'name' => $topshiriq->name,
    'money' => $topshiriq->money,
    'phone' => $topshiriq->phone
];

echo $view->render(
    'topshiriq::new',[
    'title'      => $topshiriq->name." ma'lumotlarini taxrirlash",
    'data' => $data,
    'type' => 'edit'
]);
