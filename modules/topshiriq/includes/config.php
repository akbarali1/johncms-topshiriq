<?php
function status($item){
    if ($item == '3') {
        $malumot = [
            'status' => 'АКТИВНА',
            'alert' => 'style="background-color: #00b87a;display: inline; padding: 5px; color: honeydew;"'
        ];
    } else if ($item == '2') {
        $malumot = [
            'status' => 'ПОДТВЕРЖДЕНА',
            'alert' => 'style="background-color: #135892;display: inline; padding: 5px; color: honeydew;"'
        ];
    }else {
        $malumot = [
            'status' => 'ДЕАКТИВ',
            'alert' => 'style="background-color: red;display: inline; padding: 5px; color: honeydew;"'
        ];
    }
    return $malumot;

}