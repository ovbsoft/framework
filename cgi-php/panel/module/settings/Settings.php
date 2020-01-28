<?php

namespace Run\panel\module\settings;

class Settings extends \Run\panel\core\main\Main {

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => '{ MENU }'
        ]);
    }

}
