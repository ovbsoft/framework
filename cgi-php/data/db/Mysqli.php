<?php

namespace Run\data\db;

use Run\Root;

class Mysqli {

    public static function mysqli()
    {
        $db = unserialize(file_get_contents(Root::SZ . 'base.mysql.sz'));
        $mysqli = new \mysqli(
                $db['host'],
                $db['user'],
                $db['pass'],
                $db['base']
        );
        if ($mysqli->connect_errno) {
            printf("Failed to connect: %s", $mysqli->connect_error);
            exit;
        }
        return $mysqli;
    }

    public static function query($query)
    {
        $mysqli = self::mysqli();
        $bool = $mysqli->query($query);
        $mysqli->close();
        return boolval($bool);
    }

    public static function fetch_assoc($query)
    {
        $mysqli = self::mysqli();
        $res = $mysqli->query($query);
        $row = $res->fetch_assoc();
        $res->free();
        $mysqli->close();
        return $row;
    }

}
