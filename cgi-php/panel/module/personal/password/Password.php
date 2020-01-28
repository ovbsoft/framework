<?php

namespace Run\panel\module\personal\password;

use Run\data\db\Mysqli;
use Run\panel\core\corp\Path,
    Run\panel\core\corp\login\Login;

class Password extends Save {

    use \Run\panel\core\traits\Line;

    private $confirm, $query = "
SELECT
	pass
FROM
	panel_user
WHERE
	panel_user.user = '[U]'";

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => $this->_content($this->_post())
        ]);
    }

    private function _content()
    {
        $this->line = require Path::FORM . 'line.php';
        return $this->line_button(
                        $this->line_name(
                                $this->le['pass']
                        ) . $this->_content_pass() .
                        $this->line_name(
                                $this->le['confirm']
                        ) . $this->_content_confirm()
        );
    }

    private function _content_pass()
    {
        return $this->line_div(
                        'password',
                        'pass',
                        $this->le['pass_ph'],
                        $this->pass,
                        $this->wg['pass']
        );
    }

    private function _content_confirm()
    {
        return $this->line_div(
                        'password',
                        'confirm',
                        $this->le['confirm_ph'],
                        $this->confirm,
                        $this->wg['confirm']
        );
    }

    private function _post()
    {
        $login = new Login($this->lang);
        $this->lw = $login->lw;
        $this->pass = $login->post['pass'];
        $this->confirm = $login->post['confirm'];
        $wg = require Path::HTML . 'wg.php';
        !(!empty($this->pass) or ! empty($this->confirm)) ?: $this->_empty($wg);
    }

    private function _empty($wg)
    {
        $this->_preg_match($wg);
        if (!empty($this->pass) and empty($this->confirm)) {
            if (!$this->_old($wg)) {
                $w = $this->lw['pass_confirm_enter'];
                $this->wg['confirm'] = str_replace('[W]', $w, $wg);
            }
        } elseif (empty($this->pass) and ! empty($this->confirm)) {
            $w = $this->lw['pass_enter'];
            $this->wg['pass'] = str_replace('[W]', $w, $wg);
        } elseif (!empty($this->pass) and ! empty($this->confirm)) {
            $this->_old($wg) ?: $this->_are_equal($wg);
        }
    }

    private function _old($wg)
    {
        $this->user = filter_input(2, 'panel:user');
        $query = str_replace('[U]', $this->user, $this->query);
        $old = Mysqli::fetch_assoc($query)['pass'];
        $verify = password_verify($this->pass, $old);
        if ($verify) {
            $w = $this->lw['pass_old'];
            $this->wg['pass'] = str_replace('[W]', $w, $wg);
            $this->confirm = '';
        }
        return $verify;
    }

    private function _preg_match($wg)
    {
        if (!preg_match("'^[a-z0-9]{4,32}$'i", $this->pass)) {
            $w = $this->lw['pass_format'];
            $this->wg['pass'] = str_replace('[W]', $w, $wg);
        }
    }

    private function _are_equal($wg)
    {
        if ($this->pass === $this->confirm) {
            $this->save();
        } else {
            $w = $this->lw['pass_not_match'];
            $this->wg['confirm'] = str_replace('[W]', $w, $wg);
            $this->confirm = '';
        }
    }

}
