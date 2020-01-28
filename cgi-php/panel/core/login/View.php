<?php

namespace Run\panel\core\login;

class View {

    protected $lang, $lt, $param;

    protected function view()
    {
        $view = $this->_view();
        echo str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/view.tpl')
        );
    }

    private function _view()
    {
        return[
            '{ LANG }' => $this->lang,
            '{ LT:TITLE }' => $this->lt['title'],
            '{ LT:SIGN_OUT-UPP }' => $this->lt['sign_out-upp'],
            '{ MULTILANG }' => $this->param['multilang'],
            '{ LT:ROUTE }' => $this->lt['route'],
            '{ REQUEST }' => $this->param['request'],
            '{ LT:MAIL }' => $this->lt['mail'],
            '{ MAIL:PH }' => $this->lt['mail_ph'],
            '{ MAIL }' => $this->param['mail'],
            '{ LT:PASS }' => $this->lt['pass'],
            '{ PASS:PH }' => $this->lt['pass_ph'],
            '{ WARNING }' => $this->param['warning'],
            '{ LT:SIGN_IN-UPP }' => $this->lt['sign_in-upp']
        ];
    }

}
