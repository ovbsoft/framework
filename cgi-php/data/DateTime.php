<?php

namespace Run\data;

use Run\Root;

class DateTime extends \DateTime {

    public function __construct()
    {
        parent::__construct();
        $file = Root::SZ . 'date.time.sz';
        $timezone = unserialize(file_get_contents($file))['timezone'];
        parent::setTimezone(new \DateTimeZone($timezone));
    }

}
