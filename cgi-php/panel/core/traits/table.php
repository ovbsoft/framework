<?php

namespace Run\panel\core\traits;

trait table {

    private $table;

    private function table_button($hidden, $style, $tr)
    {
        $table = [
            '{ HIDDEN }' => $hidden,
            '{ STYLE }' => $style,
            '{ TR }' => $tr,
            '{ BUTTON }' => $this->le['button']
        ];
        return str_replace(
                array_keys($table),
                array_values($table),
                file_get_contents(dirname(__DIR__) . '/form/table_button.tpl')
        );
    }

    private function table_solid_colon($block, $line)
    {
        $tr = [
            '[B]' => $block,
            '[L]' => $line
        ];
        return str_replace(
                array_keys($tr),
                array_values($tr),
                $this->table['solid_colon']
        );
    }

    private function table_double_colon($block, $line)
    {
        $tr = [
            '[B]' => $block,
            '[L]' => $line
        ];
        return str_replace(
                array_keys($tr),
                array_values($tr),
                $this->table['double_colon']
        );
    }

    private function table_select_key($array, $selected, $name)
    {
        $option = '';
        foreach ($array as $k => $v) {
            $s = $k === $selected ? ' selected' : '';
            $option .= str_replace(
                    ['[V]', '[S]', '[O]'],
                    [$k, $s, $v],
                    $this->table['option']
            );
        }
        return str_replace(
                ['[N]', '[O]'],
                [$name, $option],
                $this->table['select']
        );
    }

    private function table_select_value($array, $selected, $name)
    {
        $option = '';
        foreach ($array as $v) {
            $s = $v === $selected ? ' selected' : '';
            $option .= str_replace(
                    ['[V]', '[S]', '[O]'],
                    [$v, $s, $v],
                    $this->table['option']
            );
        }
        return str_replace(
                ['[N]', '[O]'],
                [$name, $option],
                $this->table['select']
        );
    }

    private function table_hidden($name, $value)
    {
        return str_replace(
                ['[N]', '[V]'],
                [$name, $value],
                $this->table['hidden']
        );
    }

    private function table_boolean($name, $true, $yes, $false, $no)
    {
        return str_replace(
                ['[P]', '[T]', '[Y]', '[F]', '[N]'],
                [$name, $true, $yes, $false, $no],
                $this->table['boolean']
        );
    }

}
