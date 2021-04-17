<?php
declare(strict_types = 1);

if ($request->getMethod() === 'POST'){
    
    $status = $request->getPost('status', '', FILTER_SANITIZE_NUMBER_INT);
    $table_id = $request->getPost('table_id', '', FILTER_SANITIZE_NUMBER_INT);

    $connection->table('topshiriq')
    ->where('id', '=', $table_id)
    ->update(
        [
            'status' => $status,
        ]
    );

    die(json_success("Status yangilandi"));

}else {
    die(json_error("Siz orqaga qayting bu yer siz o`ynashingiz uchun joy emas"));
}