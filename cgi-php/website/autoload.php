<?php

spl_autoload_register(function($class) {
    require dirname(__DIR__) . '/' . str_replace(
                    '\\', '/', explode('\\', $class)[0] === 'Run' ? (
                            substr($class, 4)
                            ) : 'extern/' . $class
            ) . '.php';
}, true);
