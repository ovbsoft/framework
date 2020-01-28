<?php

namespace Run\data\sz;

class Data {

    public function __construct()
    {
        file_exists(__DIR__ . '/date.time.sz') ?: $this->_date_time();
    }

    private function _date_time()
    {
        file_put_contents(__DIR__ . '/date.time.sz', serialize([
            'region' => 'europe',
            'timezone' => 'Europe/Moscow'
        ]));
        $this->_branch_run();
    }

    private function _branch_run()
    {
        file_put_contents(__DIR__ . '/branch.run.sz', serialize([
            'admin' => false,
            'ext' => '.ww'
        ]));
        $this->_panel_langs();
    }

    private function _panel_langs()
    {
        file_put_contents(__DIR__ . '/panel.langs.sz', serialize([
            'lang' => 'ru',
            'langs' => [
                'en' => 'English',
                'ru' => 'Русский'
            ],
            'multilang' => false
        ]));
        $this->_panel_login();
    }

    private function _panel_login()
    {
        file_put_contents(__DIR__ . '/panel.login.sz', serialize([
            'block' => 2,
            'timer' => 1800
        ]));
    }

}
