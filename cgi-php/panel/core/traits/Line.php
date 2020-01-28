<?php

namespace Run\panel\core\traits;

trait Line {

    private $line;

    private function line_button($line)
    {
        $table = [
            '{ LINE }' => $line,
            '{ BUTTON }' => $this->le['button']
        ];
        return str_replace(
                array_keys($table),
                array_values($table),
                file_get_contents(dirname(__DIR__) . '/form/line_button.tpl')
        );
    }

    private function line_name($name)
    {
        return str_replace('[N]', $name, $this->line['name']);
    }

    private function line_div($type, $name, $placeholder, $value, $warning)
    {
        return str_replace(
                ['[T]', '[N]', '[P]', '[V]', '[W]'],
                [$type, $name, $placeholder, $value, $warning],
                $this->line['div']
        );
    }

}
