<?php

/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

declare(strict_types=1);

use Admin\Controllers\System\SystemCheckController;
use FastRoute\RouteCollector;
use Johncms\System\Users\User;

return static function (RouteCollector $map, User $user) {
    $map->addRoute(['GET', 'POST'], '/language[/]', 'modules/language/index.php');                    // Language switcher
    $map->addRoute(['GET', 'POST'], '/login[/]', 'modules/login/index.php');                          // Login / Logout


    $map->addRoute(['GET', 'POST'], '/online/[{action}/]', 'modules/online/index.php');               // Online site activity
    $map->addRoute(['GET', 'POST'], '/profile/skl.php', 'modules/profile/skl.php');                   // Restore Password
    $map->addRoute(['GET', 'POST'], '/profile[/]', 'modules/profile/index.php');                      // User Profile
    $map->addRoute(['GET', 'POST'], '/registration[/]', 'modules/registration/index.php');            // New users registration

    if ($user->rights >= 6 && $user->isValid()) {
        $map->addRoute(['GET', 'POST'], '/admin/system_check[/]', [SystemCheckController::class, 'index']);                      // Administration
        $map->addRoute(['GET', 'POST'], '/admin/[{action}/]', 'modules/admin/index.php');                      // Administration
    }

    // Custom routes
    if (is_file(CONFIG_PATH . 'routes.local.php')) {
        /** @psalm-suppress MissingFile */
        require CONFIG_PATH . 'routes.local.php';
    }
};
