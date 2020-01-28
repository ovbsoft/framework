<?php

namespace Run\panel\module\personal\data;

use Run\data\db\Mysqli,
    Run\panel\core\corp\Path;

class Save extends \Run\panel\core\main\Main {

    use \Run\traits\Filter;

    private $ext, $query = [
                'mail' => "
UPDATE
	panel_user
SET
	mail = '[M]'
WHERE
	panel_user.user = '[U]'",
                'user' => "
UPDATE
	panel_user
SET
	user = '[U]'
WHERE
	panel_user.mail = '[M]'"
    ];
    protected $old, $mail, $user, $lw, $wg, $header;

    public function __construct($param)
    {
        $this->ext = $param['ext'];
        parent::__construct($param);
    }

    protected function mail()
    {
        $this->header = false;
        if ($this->old['mail'] !== $this->mail) {
            $s = ['[M]', '[U]'];
            $r = [$this->mail, $this->old['user']];
            $query = str_replace($s, $r, $this->query['mail']);
            if (Mysqli::query($query) !== true) {
                $this->_write('mail');
                $this->header = true;
            }
        }
        $this->_user();
    }

    private function _user()
    {
        if ($this->old['user'] !== $this->user) {
            $s = ['[U]', '[M]'];
            $r = [$this->user, $this->mail];
            $query = str_replace($s, $r, $this->query['user']);
            if (Mysqli::query($query) === true) {
                $this->_rename();
                $domain = $this->_domain();
                setcookie('panel:user', $this->user, 0, '/', $domain, true);
            } else {
                $this->_write('user');
                $this->header = true;
            }
        }
        $this->header ?: $this->_header();
    }

    private function _rename()
    {
        $old = Path::HASH . $this->old['user'] . '.sz';
        $new = Path::HASH . $this->user . '.sz';
        rename(str_replace(' ', '.', $old), str_replace(' ', '.', $new));
    }

    private function _domain()
    {
        $exp = explode('.', $this->server_http_host());
        $exp[0] !== 'www' ?: array_shift($exp);
        return '.' . implode('.', $exp);
    }

    private function _write($var)
    {
        $w = $this->lw['database_write'];
        $wg = require Path::HTML . 'wg.php';
        $this->wg[$var] = str_replace('[W]', $w, $wg);
    }

    private function _header()
    {
        header('Location: /personal' . $this->ext);
        exit;
    }

}
