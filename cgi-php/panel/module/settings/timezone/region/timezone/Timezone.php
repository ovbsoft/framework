<?php

namespace Run\panel\module\settings\timezone\region\timezone;

use Run\panel\core\corp\Path;

class Timezone extends \Run\panel\core\main\Main {

    use \Run\panel\core\traits\table;

    private $ext, $file, $region, $timezone;

    public function __construct($param)
    {
        $this->ext = $param['ext'];
        parent::__construct($param);
        $this->file = Path::SZ . 'date.time.sz';
        $this->_post(unserialize(file_get_contents($this->file)));
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _post($datetime)
    {
        $this->region = filter_has_var(0, 'post') ? (
                filter_input(0, 'region')
                ) : $datetime['region'];
        $timezone = filter_has_var(0, 'timezone');
        $this->timezone = $timezone ? (
                filter_input(0, 'timezone')
                ) : $datetime['timezone'];
        if ($timezone and $datetime['timezone'] !== $this->timezone) {
            if (in_array($this->timezone, require $this->region . '.php')) {
                $this->_write(serialize([
                    'region' => $this->region,
                    'timezone' => $this->timezone
                ]));
            }
        }
    }

    private function _write($sz)
    {
        if (boolval(file_put_contents($this->file, $sz))) {
            header('Location: /settings/timezone' . $this->ext);
            exit;
        }
    }

    private function _content()
    {
        $this->table = require Path::FORM . 'table.php';
        return $this->table_button(
                        $this->table_hidden('region', $this->region),
                        '',
                        $this->table_double_colon(
                                $this->le['choose_timezone'],
                                $this->table_select_value(
                                        require $this->region . '.php',
                                        $this->timezone,
                                        'timezone'
        )));
    }

}
