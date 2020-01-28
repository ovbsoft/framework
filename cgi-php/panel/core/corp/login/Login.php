<?php

namespace Run\panel\core\corp\login;

class Login {

    public $lw, $post = [
                'mail' => '',
                'user' => '',
                'pass' => '',
                'confirm' => ''
    ];

    public function __construct($lang)
    {
        $this->lw = require 'lang/' . $lang . '.php';
        $this->_post();
    }

    private function _post()
    {
        if (filter_has_var(0, 'mail')) {
            $this->post['mail'] = trim(filter_input(0, 'mail'));
        }
        if (filter_has_var(0, 'user')) {
            $this->post['user'] = $this->_cut_double_space();
        }
        if (filter_has_var(0, 'pass')) {
            $this->post['pass'] = trim(filter_input(0, 'pass'));
        }
        if (filter_has_var(0, 'confirm')) {
            $this->post['confirm'] = trim(filter_input(0, 'confirm'));
        }
    }

    private function _cut_double_space()
    {
        return preg_replace('/ +/', ' ', trim(filter_input(0, 'user')));
    }

}
