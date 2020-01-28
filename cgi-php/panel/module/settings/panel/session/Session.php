<?php

namespace Run\panel\module\settings\panel\session;

use Run\panel\core\corp\Path;

class Session extends \Run\panel\core\main\Main {

    use \Run\panel\core\traits\table;

    private $ext, $file, $login;

    public function __construct($param)
    {
        $this->ext = $param['ext'];
        parent::__construct($param);
        $this->file = Path::SZ . 'panel.login.sz';
        $this->login = unserialize(file_get_contents($this->file));
        !filter_has_var(0, 'post') ?: $this->_post();
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _post()
    {
        $timer = (int) filter_input(0, 'timer');
        if (isset($this->le['timer'][$timer])) {
            if ($this->login['timer'] !== $timer) {
                $this->login['timer'] = $timer;
                $this->_write(serialize($this->login));
            }
        }
    }

    private function _write($sz)
    {
        if (boolval(file_put_contents($this->file, $sz))) {
            header('Location: /settings/panel' . $this->ext);
            exit;
        }
    }

    private function _content()
    {
        $this->table = require Path::FORM . 'table.php';
        return $this->table_button(
                        '',
                        '',
                        $this->table_double_colon(
                                $this->le['block'],
                                $this->table_select_key(
                                        $this->le['timer'],
                                        $this->login['timer'],
                                        'timer'
        )));
    }

}
