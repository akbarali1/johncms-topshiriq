<?php
declare(strict_types=1);

$map->addRoute(['GET', 'POST'], '/', 'modules/login/index.php');

$map->addRoute(['GET', 'POST'], '/api/[{action}/]', 'modules/api/index.php');

$map->addRoute(['GET', 'POST'], '/topshiriq/[{action}/[{id:\d+}/]]', 'modules/topshiriq/index.php');
