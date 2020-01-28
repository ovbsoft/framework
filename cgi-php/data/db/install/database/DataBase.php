<?php

namespace Run\data\db\install\database;

use Run\panel\core\corp\Path;

class DataBase extends PanelUser {

    private $le, $lw, $wg, $list = ['host', 'user', 'pass', 'base'];

    public function __construct()
    {
        parent::lang('database');
        $tmp = file_exists(Path::SZ . 'tmp.sz');
        $tmp ? parent::tables() : parent::view($this->_content());
    }

    protected function _content()
    {
        $this->le = require 'lang/' . $this->lang . '.php';
        $view = $this->_view($this->_post());
        return str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/database.tpl')
        );
    }

    private function _post()
    {
        $post = filter_has_var(0, 'post');
        $empty = false;
        $filled = true;
        foreach ($this->list as $v) {
            $this->$v = $post ? trim(filter_input(0, $v)) : '';
            empty($this->$v) ?: $empty = true;
            !empty($this->$v) ?: $filled = false;
        }
        if ($empty) {
            $this->lw = require 'lang/wg/' . $this->lang . '.php';
            $filled ? $this->_mysql() : $this->_wg($this->lw['form']);
            boolval($this->wg) ?: $this->_save();
        }
    }

    private function _mysql()
    {
        $mysql = new \mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->base
        );
        $mysql->close() ?: $this->_wg($this->lw['mysql']);
    }

    private function _wg($w)
    {
        $this->wg = str_replace('[W]', $w, require Path::HTML . 'wg.php');
    }

    private function _save()
    {
        if (boolval(file_put_contents(Path::SZ . 'tmp.sz', serialize([
                    'host' => $this->host,
                    'user' => $this->user,
                    'pass' => $this->pass,
                    'base' => $this->base
                ]))) === false) {
            exit(
                    'Не удалось ввести данные в файл : ~/data/sz/tmp.sz'
            );
        }
        $this->_header();
    }

    private function _header()
    {
        header('Location: /');
        exit;
    }

    private function _view()
    {
        return [
            '{ LE:HOST }' => $this->le['host'],
            '{ HOST:PH }' => $this->le['host_ph'],
            '{ HOST }' => $this->host,
            '{ LE:USER }' => $this->le['user'],
            '{ USER:PH }' => $this->le['user_ph'],
            '{ USER }' => $this->user,
            '{ LE:PASS }' => $this->le['pass'],
            '{ PASS:PH }' => $this->le['pass_ph'],
            '{ PASS }' => $this->pass,
            '{ LE:BASE }' => $this->le['base'],
            '{ BASE:PH }' => $this->le['base_ph'],
            '{ BASE }' => $this->base,
            '{ WARNING }' => $this->wg ?? '',
            '{ LE:SAVE-UPP }' => $this->le['save-upp']
        ];
    }

}
