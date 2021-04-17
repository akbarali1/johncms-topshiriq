<?php
declare(strict_types=1);
use Johncms\System\Http\Request;
use Johncms\Users\User;
use Johncms\System\View\Render;
use Johncms\NavChain;
use Johncms\System\i18n\Translator;
defined('_IN_JOHNCMS') || die('Error: restricted access');

/** @var User $user */
$user = di(User::class);

/** @var Render $view */
$view = di(Render::class);

/** @var NavChain $nav_chain */
$nav_chain = di(NavChain::class);

/** @var Request $request */
$request = di(Request::class);

$config = di('config')['johncms'];
$route = di('route');
$connection = \Illuminate\Database\Capsule\Manager::connection();
// Register Namespace for module templates
$view->addFolder('topshiriq', __DIR__ . '/templates/');

$id = $route['id'] ?? '';
$act = $route['action'] ?? '';

// Register the module languages domain and folder
di(Translator::class)->addTranslationDomain('topshiriq', __DIR__ . '/locale');

$title = __('indextitle');

if (!$user->is_valid) {
    echo $view->render(
        'topshiriq::result',
        [
            'title'    => $title,
            'message'  => __('mehmonlarga yopiq'),
            'type'     => 'error',
            'back_url' => '/',
        ]
    );
    exit;
}
  
switch ($act) {

    case 'new':
        require 'includes/new.php';
        break;

    case 'view':
        require 'includes/view.php';
        break;

    case 'edit':
        require 'includes/edit.php';
        break;
    
    case 'delete':
        require 'includes/delete.php';
        break;

    default:
        require 'includes/index.php';
        break;
}
