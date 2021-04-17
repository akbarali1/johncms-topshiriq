<?php

/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

declare(strict_types=1);

use Illuminate\Support\Str;
use Johncms\System\Http\Request;
use Johncms\System\Legacy\Tools;
use Johncms\System\View\Render;
use Johncms\NavChain;
use Johncms\Validator\Validator;
use Johncms\Users\User;

defined('_IN_JOHNCMS') || die('Error: restricted access');

/**
 * @var Request $request
 * @var User $user
 * @var Render $view
 * @var NavChain $nav_chain
 */

$config = di('config')['johncms'];
$request = di(Request::class);
$user = di(User::class);
$view = di(Render::class);
$nav_chain = di(NavChain::class);

// Регистрируем Namespace для шаблонов модуля
$view->addFolder('login', __DIR__ . '/templates/');

$id = $request->getPost('id', 0, FILTER_SANITIZE_NUMBER_INT);
$referer = $request->getServer('HTTP_REFERER', $config['homeurl'], FILTER_SANITIZE_SPECIAL_CHARS);

if ($user->isValid()) {
    header('Location: '.$config['homeurl']."/topshiriq/");
} else {
    ////////////////////////////////////////////////////////////
    // Вход на сайт                                           //
    ////////////////////////////////////////////////////////////

    /** @var PDO $db */
    $db = di(PDO::class);

    /** @var Tools $tools */
    $tools = di(Tools::class);

    $nav_chain->add(__('Login'));

    $error = [];
    $captcha = false;
    $display_form = 1;
    if ($request->getPost('types', null, FILTER_SANITIZE_STRING) == 'login' ) {
    $user_login = $request->getPost('n', null, FILTER_SANITIZE_STRING);
    $user_pass = $request->getPost('p', null, FILTER_SANITIZE_STRING);
    $captchaCode = $request->getPost('code', null, FILTER_SANITIZE_STRING);
    
    if (empty($user_login)) {
        $error[] = __('You have not entered login');
    }

    if (empty($user_pass)) {
        $error[] = __('You have not entered password');
    }

    if (! $error) {
       
        // Запрос в базу на юзера
        $stmt = $db->prepare('SELECT * FROM `users` WHERE `name_lat` = ? LIMIT 1');
        $stmt->execute([Str::slug($user_login, '_')]);

        if ($stmt->rowCount()) {
            $loginUser = new User($stmt->fetch());

            if ($loginUser->failed_login > 2) {
                if ($captchaCode) {
                    if (mb_strlen($captchaCode) > 2 && strtolower($captchaCode) === strtolower($_SESSION['code'])) {
                        // Если введен правильный проверочный код
                        $captcha = true;
                    } else {
                        // Если проверочный код указан неверно
                        $error[] = __('The security code is not correct');
                    }

                    unset($_SESSION['code']);
                } else {
                    // Показываем CAPTCHA
                    $display_form = 0;
                    $code = (string) new Mobicms\Captcha\Code();
                    $_SESSION['code'] = $code;
                    echo $view->render(
                        'login::captcha',
                        [
                            'captcha'    => new Mobicms\Captcha\Image($code),
                            'user_login' => $user_login,
                            'user_pass'  => $user_pass,
                            'id'         => $loginUser->id,
                        ]
                    );
                }
            }

            if ($loginUser->failed_login < 3 || $captcha) {
                if (md5(md5($user_pass)) == $loginUser->password) {
                    // Если логин удачный
                    $display_form = 0;
                    $db->exec("UPDATE `users` SET `failed_login` = '0' WHERE `id` = " . $loginUser->id);

                    if (! $loginUser->email_confirmed && $config['user_email_confirmation']) {
                        // Показываем сообщение о неподтвержденной регистрации
                        echo $view->render('login::confirm', ['confirm' => 'email']);
                    } elseif (! $loginUser->preg) {
                        // Показываем сообщение о неподтвержденной регистрации
                        echo $view->render('login::confirm', ['confirm' => 'moderation']);
                    } else {
                        // Если все проверки прошли удачно, подготавливаем вход на сайт
                        setcookie('cuid', (string) $loginUser->id, time() + 3600 * 24 * 365, '/');
                        setcookie('cups', md5($user_pass), time() + 3600 * 24 * 365, '/');

                        $db->exec("UPDATE `users` SET `sestime` = '" . time() . "' WHERE `id` = " . $loginUser->id);
                        header('Location: /');
                        exit;
                    }
                } else {
                    // Если логин неудачный
                    if ($loginUser->failed_login < 3) {
                        // Прибавляем к счетчику неудачных логинов
                        $failed_login = $loginUser->failed_login + 1;
                        $db->exec("UPDATE `users` SET `failed_login` = '" . $failed_login . "' WHERE `id` = " . $loginUser->id);
                    }

                    $error[] = __('Authorization failed');
                }
            }
        } else {
            $error[] = __('Authorization failed');
        }
    }

}

if ($request->getPost('types', null, FILTER_SANITIZE_STRING) == 'reg' ) {
    // die(d($_POST));
    $fields = [
        'name'     => $request->getPost('name', '', FILTER_SANITIZE_STRING),
        'name_lat' => Str::slug($request->getPost('name', '', FILTER_SANITIZE_STRING), '_'),
        'password' => $request->getPost('password', ''),
        'sex'      => $request->getPost('sex', ''),
        'imname'   => $request->getPost('imname', '', FILTER_SANITIZE_STRING),
        'about'    => $request->getPost('about', '', FILTER_SANITIZE_STRING),
        'captcha'  => $request->getPost('captcha', null),
        'email'    => $request->getPost('email', ''),
    ];
 $rules = [
    'name'     => [
        'NotEmpty',
        'StringLength'   => ['min' => 2, 'max' => 20],
        'ModelNotExists' => [
            'model' => \Johncms\Users\User::class,
            'field' => 'name',
        ],
    ],
    'name_lat' => [
        'ModelNotExists' => [
            'model' => \Johncms\Users\User::class,
            'field' => 'name_lat',
        ],
    ],
    'password' => [
        'NotEmpty',
        'StringLength' => ['min' => 6],
    ],
    'sex'      => [
        'InArray' => ['haystack' => ['m', 'zh']],
    ],
    'captcha'  => ['Captcha'],
];

$validator = new Validator($fields, $rules);

if ($validator->isValid()) {
    /** @var Johncms\System\Http\Environment $env */
    $env = di(Johncms\System\Http\Environment::class);
    $connection = \Illuminate\Database\Capsule\Manager::connection();
   $new_user = (new User())->create(
        [
            'name'         => $fields['name'],
            'name_lat'     => $fields['name_lat'],
            'password'     => md5(md5($fields['password'])),
            'imname'       => $fields['imname'],
            'about'        => $fields['about'],
            'sex'          => $fields['sex'],
            'mail'         => $fields['email'],
            'rights'       => 0,
            'ip'           => '127.0.0.1',
            'ip_via_proxy' => $env->getIpViaProxy(false),
            'browser'      => $env->getUserAgent(),
            'datereg'      => time(),
            'lastdate'     => time(),
            'sestime'      => time(),
            'preg'         => $config['mod_reg'] > 1 ? 1 : 0,
            'set_user'     => [],
            'set_forum'    => [],
            'set_mail'     => [],
            'smileys'      => [],
            'email_confirmed'   => 1,
            'confirmation_code' => null,
        ]
    );

    if ($config['mod_reg'] !== 1 && empty($config['user_email_confirmation'])) {
        setcookie('cuid', (string) $new_user->id, time() + 3600 * 24 * 365, '/');
        setcookie('cups', md5($fields['password']), time() + 3600 * 24 * 365, '/');
    }

    
    echo $view->render(
        'login::registration_result',
        [
            'usid'     => $new_user->id,
            'reg_nick' => $fields['name'],
            'reg_pass' => $fields['password'],
        ]
    );
    exit;
}

$errors = $validator->getErrors();
unset($_SESSION['code']);
}

    if ($display_form) {
    $code = (string) new Mobicms\Captcha\Code();
    $_SESSION['code'] = $code;
        // Показываем LOGIN форму
        echo $view->render(
            'login::login',
            [
                'error'      => isset($_POST['login']) ? $error : [],
                'user_login' => $user_login ?? '',
                'captcha' => new Mobicms\Captcha\Image($code),
            ]
        );
    }
}
