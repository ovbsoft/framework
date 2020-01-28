<?php

namespace Run\data\db\install\admin;

use Run\data\db\Mysqli;
use Run\panel\core\corp\Path,
    Run\panel\core\corp\login\Login;

class Admin extends \Run\data\db\install\View {

    private $le, $lw, $wg,
            $mail = '', $user = '', $pass = '', $confirm = '',
            $list = ['mail', 'user', 'pass', 'confirm'],
            $query = "
SELECT 
	`[K]`
FROM 
	`panel_user`
WHERE 
	`[K]` = '[V]'";

    public function __construct()
    {
        $this->_post($this->_login($this->lang('admin')));
    }

    private function _login()
    {
        $login = new Login($this->lang);
        $this->lw = $login->lw;
        $this->mail = $login->post['mail'];
        $this->user = $login->post['user'];
        $this->pass = $login->post['pass'];
        $this->confirm = $login->post['confirm'];
        foreach ($this->list as $v) {
            $this->wg[$v] = '';
        }
    }

    private function _post()
    {
        if (
                boolval($this->mail) or
                boolval($this->user) or
                boolval($this->pass) or
                boolval($this->confirm)
        ) {
            $this->_mail();
        } else {
            $this->_content();
        }
    }

    private function _mail()
    {
        $w = '';
        $wg = require Path::HTML . 'wg.php';
        if (empty($this->mail)) {
            $w = $this->lw['mail_enter'];
        } elseif (strpos($this->mail, ' ') !== false) {
            $w = $this->lw['mail_emptyh'];
        } elseif (!preg_match("'.+@.+\..+'i", $this->mail)) {
            $w = $this->lw['mail_format'];
        } elseif (strlen($this->mail) > 255) {
            $w = $this->lw['mail_length'];
        } elseif ($this->_query('mail', $this->mail)) {
            $w = $this->lw['mail_exists'];
        }
        empty($w) ?: $this->wg['mail'] = str_replace('[W]', $w, $wg);
        $this->_user($wg);
    }

    private function _user($wg)
    {
        $w = '';
        if (empty($this->user)) {
            $w = $this->lw['user_enter'];
        } elseif (!preg_match("'^[a-z0-9\-\_\. ]{2,32}$'i", $this->user)) {
            $w = $this->lw['user_format'];
        } elseif ($this->_query('user', $this->user)) {
            $w = $this->lw['user_exists'];
        }
        empty($w) ?: $this->wg['user'] = str_replace('[W]', $w, $wg);
        $this->_pass($wg);
    }

    private function _query($k, $v)
    {
        $query = str_replace(['[K]', '[V]'], [$k, $v], $this->query);
        return boolval(Mysqli::fetch_assoc($query));
    }

    private function _pass($wg)
    {
        $w = '';
        if (empty($this->pass)) {
            $w = $this->lw['pass_enter'];
        } elseif (!preg_match("'^[a-z0-9]{4,32}$'i", $this->pass)) {
            $w = $this->lw['pass_format'];
        }
        empty($w) ?: $this->wg['pass'] = str_replace('[W]', $w, $wg);
        $this->_confirm($wg);
    }

    private function _confirm($wg)
    {
        $w = '';
        if (empty($this->confirm)) {
            $w = $this->lw['pass_confirm_enter'];
        } elseif ($this->pass !== $this->confirm) {
            $this->confirm = '';
            $w = $this->lw['pass_not_match'];
        }
        empty($w) ?: $this->wg['confirm'] = str_replace('[W]', $w, $wg);
        $this->_check();
    }

    private function _check()
    {
        $empty = true;
        $wg = true;
        foreach ($this->list as $v) {
            !empty($this->$v) ?: $empty = false;
            empty($this->wg[$v]) ?: $wg = false;
        }
        if ($empty and $wg) {
            new Save([
                'mail' => $this->mail,
                'user' => $this->user,
                'pass' => $this->pass
            ]);
        } else {
            $this->_content();
        }
    }

    private function _content()
    {
        $this->le = require 'lang/' . $this->lang . '.php';
        $view = $this->_view();
        parent::view(str_replace(
                        array_keys($view),
                        array_values($view),
                        file_get_contents(__DIR__ . '/admin.tpl')
        ));
    }

    private function _view()
    {
        return[
            '{ LE:MAIL }' => $this->le['mail'],
            '{ LE:MAIL_PH }' => $this->le['mail_ph'],
            '{ MAIL }' => $this->mail,
            '{ MAIL:WG }' => $this->wg['mail'],
            '{ LE:USER }' => $this->le['user'],
            '{ LE:USER_PH }' => $this->le['user_ph'],
            '{ USER }' => $this->user,
            '{ USER:WG }' => $this->wg['user'],
            '{ LE:PASS }' => $this->le['pass'],
            '{ LE:PASS_PH }' => $this->le['pass_ph'],
            '{ PASS }' => $this->pass,
            '{ PASS:WG }' => $this->wg['pass'],
            '{ LE:CONFIRM }' => $this->le['confirm'],
            '{ LE:CONFIRM_PH }' => $this->le['confirm_ph'],
            '{ CONFIRM }' => $this->confirm,
            '{ CONFIRM:WG }' => $this->wg['confirm'],
            '{ LE:REGISTRATION-UPP }' => $this->le['registration-upp']
        ];
    }

}
