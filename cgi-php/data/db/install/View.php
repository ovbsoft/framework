<?php

namespace Run\data\db\install;

use Run\panel\core\corp\Lang;

class View {

    private $lt, $multilang;
    protected $lang;

    protected function lang($class)
    {
        $lang = new Lang;
        $this->lang = $lang->lang;
        $this->lt = require 'lang/' . $class . '/' . $lang->lang . '.php';
        $this->multilang = $lang->multilang();
    }

    protected function view($content)
    {
        $view = [
            '{ LANG }' => $this->lang,
            '{ LT:TITLE }' => $this->lt['title'],
            '{ MULTILANG }' => $this->multilang,
            '{ LT:ROUTE }' => $this->lt['route'],
            '{ CONTENT }' => $content,
            '{ REQUEST }' => '/'
        ];
        echo str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/view.tpl')
        );
    }

}
