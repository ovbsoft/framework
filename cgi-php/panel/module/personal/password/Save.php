<?php

namespace Run\panel\module\personal\password;

use Run\data\db\Mysqli,
    Run\panel\core\corp\Path;

class Save extends \Run\panel\core\main\Main {

    private $ext, $query = "
UPDATE
	panel_user
SET
	pass = '[P]'
WHERE
	panel_user.user = '[U]'";
    protected $user, $pass, $lw, $wg;

    public function __construct($param)
    {
        $this->ext = $param['ext'];
        parent::__construct($param);
    }

    protected function save()
    {
        $s = ['[P]', '[U]'];
        $r = [password_hash($this->pass, PASSWORD_DEFAULT), $this->user];
        $query = str_replace($s, $r, $this->query);
        if (Mysqli::query($query) === true) {
            $this->_header();
        } else {
            $w = $this->lw['database_failed'];
            $wg = Path::HTML . 'wg.php';
            $this->wg['confirm'] = str_replace('[W]', $w, $wg);
        }
    }

    private function _header()
    {
        header('Location: /personal' . $this->ext);
        exit;
    }

}
