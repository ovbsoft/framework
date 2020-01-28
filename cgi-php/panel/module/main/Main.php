<?php

namespace Run\panel\module\main;

class Main extends \Run\panel\core\main\Main {

    public function __construct($param)
    {
        parent::__construct($param);
        parent::view([
            'content' => '{ MENU }'
        ]);
    }

}
