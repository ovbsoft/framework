<?php

namespace Run\panel\core\route;

use Run\data\db\Mysqli,
    Run\panel\core\corp\Path;

class Login extends \DateTime {

    use \Run\traits\Filter,
        \Run\traits\Hash;

    private $timestamp, $mysqli, $user, $pass, $hash, $post, $agent, $domain,
            $query = [
                'select' => "
SELECT
	panel_user.id id,
	panel_user.user user,
	panel_user.pass pass,
	panel_user.timestamp timestamp
FROM
	panel_user
WHERE
	panel_user.mail = '[M]'",
                'update' => "
UPDATE
	panel_user
SET
	timestamp = [T]
WHERE
	panel_user.id = [I]",
                'timestamp' => 0
    ];
    protected $error, $param;

    public function __construct($param)
    {
        parent::__construct();
        $this->param = $param;
        $this->timestamp = parent::getTimestamp();
        filter_has_var(0, 'login') ? $this->_login() : $this->_cookie();
    }

    private function _login()
    {
        $this->post['mail'] = trim(filter_input(0, 'mail'));
        $this->post['pass'] = trim(filter_input(0, 'pass'));
        if (!empty($this->post['mail']) and ! empty($this->post['pass'])) {
            $this->_mail();
        } else {
            $this->param['user']['wg'] = false;
        }
    }

    private function _mail()
    {
        $this->mysqli = Mysqli::mysqli();
        $id = $this->_query();
        $wg = boolval($id);
        if ($wg) {
            $s = ['[T]', '[I]'];
            $r = [$this->timestamp, $id];
            $query = str_replace($s, $r, $this->query['update']);
            $this->mysqli->query($query);
        }
        $this->mysqli->close();
        if ($wg) {
            $this->_block();
        } else {
            $this->param['user']['wg'] = 1;
        }
    }

    private function _query()
    {
        $mail = $this->post['mail'];
        $query = str_replace('[M]', $mail, $this->query['select']);
        $res = $this->mysqli->query($query);
        $row = $res->fetch_assoc();
        $res->free();
        if (boolval($row)) {
            $this->user = $row['user'];
            $this->pass = $row['pass'];
            $this->query['timestamp'] = $row['timestamp'];
            return $row['id'];
        }
    }

    private function _block()
    {
        $login = unserialize(file_get_contents(Path::SZ . 'panel.login.sz'));
        if ($this->timestamp - $login['block'] > $this->query['timestamp']) {
            $this->_password();
        } else {
            $this->param['user']['wg'] = 2;
        }
    }

    private function _password()
    {
        if (password_verify($this->post['pass'], $this->pass)) {
            $this->_domain();
            setcookie('panel:user', $this->user, 0, '/', $this->domain, true);
            $this->agent = $this->server_user_agent();
            $this->_hash();
        } else {
            $this->param['user']['wg'] = 1;
        }
    }

    private function _cookie()
    {
        $this->user = filter_input(2, 'panel:user');
        $this->hash = filter_input(2, 'panel:hash');
        if ($this->user and $this->hash) {
            $this->_timer();
        } else {
            $this->param['user']['wg'] = false;
        }
    }

    private function _timer()
    {
        $name = str_replace(' ', '.', $this->user);
        $file = Path::HASH . $name . '.sz';
        $hash = unserialize(file_get_contents($file));
        $login = unserialize(file_get_contents(Path::SZ . 'panel.login.sz'));
        $this->agent = $this->server_user_agent();
        if (
                $this->hash === $hash['hash'] and
                $this->timestamp - $login['timer'] < $hash['time'] and
                $this->agent === $hash['agent']
        ) {
            $this->_hash($this->_domain());
        } else {
            $this->param['user']['wg'] = 3;
        }
    }

    private function _domain()
    {
        $exp = explode('.', $this->server_http_host());
        $exp[0] !== 'www' ?: array_shift($exp);
        $this->domain = '.' . implode('.', $exp);
    }

    private function _hash()
    {
        $hash = $this->hash(32);
        setcookie('panel:hash', $hash, 0, '/', $this->domain, true);
        $file = Path::HASH . str_replace(' ', '.', $this->user) . '.sz';
        if (file_put_contents($file, serialize([
                    'hash' => $hash,
                    'time' => $this->timestamp,
                    'agent' => $this->agent
                ]))) {
            
        } else {
            $this->param['user']['wg'] = 4;
        }
    }

}
