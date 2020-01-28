<?php

namespace Run\panel\module\settings\panel\login;

use Run\panel\core\corp\Path;

class Login extends \Run\panel\core\main\Main {

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
        $block = (int) filter_input(0, 'block');
        if (isset($this->le['timer'][$block])) {
            if ($this->login['block'] !== $block) {
                $this->login['block'] = $block;
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
                                        $this->login['block'],
                                        'block'
        )));
    }

}
