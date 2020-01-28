<?php

namespace Run\website\view\base;

class View {

    private $lt, $ext, $param;
    protected $lang, $html;

    protected function view($param)
    {
        $view = $this->_view($this->_param($param));
        echo str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/view.tpl')
        );
    }

    private function _param($param)
    {
        $this->lt = require 'lang/' . $this->lang . '.php';
        $this->ext = '.' . $this->lang;
        $this->param = $param;
    }

    private function _view()
    {
        return[
            '{ LT:TITLE }' => $this->lt['title'],
            '{ LOGO }' => $this->param['request'],
            '{ LANG }' => $this->lang,
            '{ LT:SIGN_IN-UPP }' => $this->lt['sign_in-upp'],
            '{ CONTENT }' => $this->param['content'],
            '{ REQUEST }' => $this->param['request'],
            '{ PANEL }' => $this->param['panel'],
            '{ EXT }' => $this->ext
        ];
    }

}
