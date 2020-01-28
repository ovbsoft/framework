<?php

namespace Run\panel\module\settings\panel;

class Panel extends \Run\panel\core\main\Main {

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => '{ MENU }'
        ]);
    }

}
