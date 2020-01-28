<?php

namespace Run\data\db\install;

use Run\Root;

class Install {

    public function __construct($request)
    {
        error_reporting(0);
        if ($request === '/') {
            if (file_exists(Root::SZ . 'base.mysql.sz')) {
                new admin\Admin;
            } else {
                new database\DataBase;
            }
        } else {
            header('Location: /');
            exit;
        }
    }

}
