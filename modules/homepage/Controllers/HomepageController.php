<?php

/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

namespace Homepage\Controllers;

use Johncms\Controller\BaseController;

class HomepageController extends BaseController
{
    protected $module_name = 'homepage';

    public function index(): string
    {
        define('_IS_HOMEPAGE', 1);
        $this->nav_chain->showHomePage(false);

        $config = di('config')['johncms'];
        $this->render->addData(
            [
                'title'       => $config['meta_title'] ?? '',
                'keywords'    => $config['meta_key'],
                'description' => $config['meta_desc'],
            ]
        );

        $data = [];

        $data['news'] = 'salom';

        return $this->render->render('homepage::index', ['data' => $data]);
    }
}
