<?php
declare(strict_types=1);
use Johncms\System\Http\Request;
use Johncms\Users\User;
require_once("config.php");
defined('_IN_JOHNCMS') || die('Error: restricted access');
/** @var User $user */
$user = di(User::class);
/** @var Request $request */
$request = di(Request::class);
$config = di('config')['johncms'];
$route = di('route');
$id = $request->getQuery('id', 0, FILTER_VALIDATE_INT);
$act = $route['action'] ?? '';

if (!$user->is_valid) {
    echo json_error("Error Code: 1");
    exit;
}
$connection = \Illuminate\Database\Capsule\Manager::connection();
header("Content-Type: application/json");
switch ($act) {
   case 'status':
      require 'includes/status.php';
      break;

    default:
        require 'includes/index.php';
        break;
}
