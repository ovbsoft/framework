<?php

namespace Run\data\db\install\database;

use Run\Root,
    Run\data\db\Mysqli;

class PanelUser extends \Run\data\db\install\View {

    private $mysqli,
            $panel_user = '
CREATE TABLE `panel_user` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`mail` VARCHAR(255) NOT NULL,
	`user` VARCHAR(255) NOT NULL,
	`pass` VARCHAR(128) NOT NULL,
	`date_created` INT(11) NOT NULL,
	`timestamp` INT(11) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE (`mail`),
	UNIQUE (`user`)
) ENGINE = InnoDB;';
    protected $host, $user, $pass, $base;

    protected function tables()
    {
        $sz = Root::SZ . 'base.mysql.sz';
        $data_base_sz = file_get_contents(Root::SZ . 'tmp.sz');
        unlink(Root::SZ . 'tmp.sz');
        if (boolval(file_put_contents($sz, $data_base_sz)) === false) {
            exit(
                    'Не удалось ввести данные в файл : ' .
                    '~/branch/sz/data.base.sz'
            );
        }
        $this->_panel_user();
    }

    private function _panel_user()
    {
        $this->mysqli = Mysqli::mysqli();
        if ($this->mysqli->query($this->panel_user) === true) {
            $this->mysqli->close();
            $this->_header();
        } else {
            $this->mysqli->close();
            exit('Не создана таблица [panel_user]');
        }
    }

    private function _header()
    {
        header('Location: /');
        exit;
    }

}
