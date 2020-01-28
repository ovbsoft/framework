<?php

namespace Run\data\db\install\admin;

use Run\data\db\Mysqli,
    Run\panel\core\corp\Path;

class Save extends \DateTime {

    use \Run\traits\Filter,
        \Run\traits\Hash;

    private $mysqli, $mail, $user, $pass, $timestamp,
            $panel_user = "
INSERT INTO `panel_user` (
        `id`, `mail`, `user`, `pass`, `date_created`, `timestamp`
) 
VALUES  (
        NULL, '[M]', '[U]', '[P]', '[D]', '[T]'
)";

    public function __construct($param)
    {
        parent::__construct();
        $this->mail = $param['mail'];
        $this->user = $param['user'];
        $this->pass = $param['pass'];
        $this->timestamp = $this->getTimestamp();
        $this->mysqli = Mysqli::mysqli();
        $this->_panel_user();
    }

    private function _panel_user()
    {
        $s = ['[M]', '[U]', '[P]', '[D]', '[T]'];
        $r = [
            $this->mail,
            $this->user,
            password_hash($this->pass, PASSWORD_DEFAULT),
            $this->timestamp,
            $this->timestamp
        ];
        $query = str_replace($s, $r, $this->panel_user);
        if ($this->mysqli->query($query) === true) {
            $this->_hash();
        } else {
            $this->mysqli->close();
            exit('Не создана таблица администратора [panel_user]');
        }
    }

    private function _hash()
    {
        unlink(Path::HASH . 'hash');
        $hash = $this->hash(32);
        $sz = [
            'hash' => $hash,
            'time' => $this->timestamp,
            'agent' => $this->server_user_agent()
        ];
        $file = Path::HASH . str_replace(' ', '.', $this->user) . '.sz';
        file_put_contents($file, serialize($sz));
        $this->_setcookie($hash);
    }

    private function _setcookie($hash)
    {
        $exp = explode('.', $this->server_http_host());
        $exp[0] !== 'www' ?: array_shift($exp);
        $domain = '.' . implode('.', $exp);
        setcookie('panel:user', $this->user, 0, '/', $domain, true);
        setcookie('panel:hash', $hash, 0, '/', $domain, true);
        $this->_branch();
    }

    private function _branch()
    {
        $file = Path::SZ . 'branch.run.sz';
        $run = unserialize(file_get_contents($file));
        $run['admin'] = $this->user;
        file_put_contents($file, serialize($run));
        $this->_header($run['ext']);
    }

    private function _header($ext)
    {
        header('Location: /personal' . $ext);
        exit;
    }

}
