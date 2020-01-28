<?php

namespace Run\panel\core\route;

use Run\panel\core\corp\Path;

class Param extends Login {

    protected $class;

    public function __construct($param)
    {
        $class = end(explode('/', $param['path']));
        $this->class = mb_convert_case($class, MB_CASE_TITLE);
        $this->error = false;
        $file = Path::MODULE . $param['path'] . '/' . $this->class . '.php';
        if ($param['error'] ? $param['error'] : !file_exists($file)) {
            $this->error = true;
            $param['error'] = '404';
        } else {
            unset($param['error']);
        }
        parent::__construct($param);
    }

}
