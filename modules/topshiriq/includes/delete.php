<?php
declare(strict_types=1);

if ($request->getMethod() === 'POST'){
    
    $table_id = $request->getPost('table_id', '', FILTER_VALIDATE_INT);
    $connection->table('topshiriq')->where('id', '=', $table_id)->delete();
    header('Location: '.$config['homeurl']."/topshiriq/");
    die;
}

echo $view->render(
    'topshiriq::delete',[
    'title'      => "Malumotni  o`chirish",
]);
