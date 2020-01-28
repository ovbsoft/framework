<?php

namespace Run\panel\module\personal\data;

use Run\data\db\Mysqli;
use Run\panel\core\corp\Path,
    Run\panel\core\corp\login\Login;

class Data extends Save {

    use \Run\panel\core\traits\Line;

    private $query = [
        'old' => "
SELECT
	mail
FROM
	panel_user
WHERE
	panel_user.user = '[U]'",
        'check' => "
SELECT 
	[K]
FROM 
	panel_user
WHERE 
	[K] = '[V]'"
    ];

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => $this->_content($this->_post($this->_mysql()))
        ]);
    }

    private function _content()
    {
        $this->line = require Path::FORM . 'line.php';
        return $this->line_button(
                        $this->line_name(
                                $this->le['mail']
                        ) . $this->_content_mail() .
                        $this->line_name(
                                $this->le['user']
                        ) . $this->_content_user()
        );
    }

    private function _content_mail()
    {
        return $this->line_div(
                        'text',
                        'mail',
                        $this->le['mail_ph'],
                        $this->mail,
                        $this->wg['mail']
        );
    }

    private function _content_user()
    {
        return $this->line_div(
                        'text',
                        'user',
                        $this->le['user_ph'],
                        $this->user,
                        $this->wg['user']
        );
    }

    private function _mysql()
    {
        $this->user = filter_input(2, 'panel:user');
        $query = str_replace('[U]', $this->user, $this->query['old']);
        $row = Mysqli::fetch_assoc($query);
        $this->old['mail'] = $row['mail'];
        $this->old['user'] = $this->user;
    }

    private function _post()
    {
        if (filter_has_var(0, 'post')) {
            $login = new Login($this->lang);
            $this->lw = $login->lw;
            $this->mail = $login->post['mail'];
            $this->user = $login->post['user'];
            $this->_mail();
        } else {
            $this->mail = $this->old['mail'];
            $this->user = $this->old['user'];
        }
    }

    private function _mail()
    {
        $w = '';
        if ($this->mail !== $this->data['data']['mail']) {
            if (empty($this->mail)) {
                $w = $this->lw['mail_enter'];
            } elseif (strpos($this->mail, ' ') !== false) {
                $w = $this->lw['mail_emptyh'];
            } elseif (!preg_match("'.+@.+\..+'i", $this->mail)) {
                $w = $this->lw['mail_format'];
            } elseif (strlen($this->mail) > 255) {
                $w = $this->lw['mail_length'];
            } elseif ($this->_exist('mail', $this->mail)) {
                $w = $this->lw['mail_exists'];
            }
        }
        empty($w) ?: $this->_wg('mail', $w);
        $this->_user();
    }

    private function _user()
    {
        $w = '';
        if ($this->user !== $this->data['user']['user']) {
            if (empty($this->user)) {
                $w = $this->lw['user_enter'];
            } elseif (!preg_match("'^[a-z0-9\-_ ]{2,32}$'i", $this->user)) {
                $w = $this->lw['user_format'];
            } elseif ($this->_exist('user', $this->user)) {
                $w = $this->lw['user_exists'];
            }
        }
        empty($w) ?: $this->_wg('user', $w);
        $this->_save();
    }

    private function _exist($k, $v)
    {
        $query = str_replace(['[K]', '[V]'], [$k, $v], $this->query['check']);
        if ($this->old[$k !== $v]) {
            return boolval(Mysqli::fetch_assoc($query));
        }
        return false;
    }

    private function _wg($wg, $w)
    {
        $this->wg[$wg] = str_replace('[W]', $w, require Path::HTML . 'wg.php');
    }

    private function _save()
    {
        $empty = (
                empty($this->wg['mail']) and
                empty($this->wg['user'])
                );
        $equal = (
                $this->mail !== $this->old['mail'] or
                $this->user !== $this->old['user']
                );
        if ($empty and $equal) {
            $this->mail();
        }
    }

}
