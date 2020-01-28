<?php

namespace Run\panel\core\login;

use Run\panel\core\corp\{
    Path,
    Lang
};

class Login extends View {

    private $lw;

    public function __construct($param)
    {
        $lang = new Lang;
        $this->lang = $lang->lang;
        $this->lw = require 'lang/wg/' . $lang->lang . '.php';
        $this->lt = require 'lang/' . $lang->lang . '.php';
        $this->param['multilang'] = $lang->multilang();
        $this->param['request'] = $param['request'];
        $this->param['mail'] = filter_has_var(0, 'mail') ? (
                trim(filter_input(0, 'mail'))
                ) : '';
        $this->param['warning'] = $this->_switch($param['wg']);
        parent::view();
    }

    private function _switch($wg)
    {
        switch ($wg) {
            case 1: $w = $this->lw['incorrect'];
                break;
            case 2: $w = $this->lw['blocked'];
                break;
            case 3: $w = $this->lw['timeout'];
                break;
            case 4: $w = $this->lw['server'];
                break;
        }
        return isset($w) ? (
                str_replace('[W]', $w, require Path::HTML . 'wg.php')
                ) : '';
    }

}
