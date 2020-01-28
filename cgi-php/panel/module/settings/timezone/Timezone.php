<?php

namespace Run\panel\module\settings\timezone;

use Run\panel\core\corp\Path;

class Timezone extends \Run\panel\core\main\Main {

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _content()
    {
        $timezone = [
            '{ LE:TIME }' => $this->le['time'],
            '{ TIMEZONE }' => $this->_timezone(),
            '{ LE:CHANGE }' => $this->le['change']
        ];
        return str_replace(
                array_keys($timezone),
                array_values($timezone),
                file_get_contents(__DIR__ . '/timezone.tpl')
        );
    }

    private function _timezone()
    {
        $file = Path::SZ . '/date.time.sz';
        return unserialize(file_get_contents($file))['timezone'];
    }

}
