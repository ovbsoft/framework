<?php

namespace Run\panel\module\settings\timezone\region;

use Run\panel\core\corp\Path;

class Region extends \Run\panel\core\main\Main {

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _content()
    {
        $region = [
            '{ LE:CHOOSE_REGION }' => $this->le['choose_region'],
            '{ REGION }' => $this->_region(),
            '{ LE:SELECT }' => $this->le['select']
        ];
        return str_replace(
                array_keys($region),
                array_values($region),
                file_get_contents(__DIR__ . '/region.tpl')
        );
    }

    private function _region()
    {
        $html = require Path::HTML . 'option.php';
        $file = Path::SZ . 'date.time.sz';
        $region = unserialize(file_get_contents($file))['region'];
        $list = require 'timezone/lang/region/' . $this->lang . '.php';
        $option = '';
        foreach ($list as $k => $v) {
            $option .= str_replace(['[V]', '[O]'], [$k, $v], $html[
                    $k === $region ? 'option-selected' : 'option'
            ]);
        }
        return $option;
    }

}
