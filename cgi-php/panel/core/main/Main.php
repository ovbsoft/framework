<?php

namespace Run\panel\core\main;

use Run\panel\core\corp\{
    Path,
    Lang
};

class Main extends View {

    public function __construct($param)
    {
        parent::__construct($this->_param($param));
        !isset($param['error']) ?: $this->_error($param['error']);
    }

    private function _param($param)
    {
        $html = require Path::HTML . 'main.php';
        $lang = new Lang;
        $this->lang = $lang->lang;
        $param['multilang'] = $lang->multilang();
        $param['head'] = $this->_head($param['path'], $html);
        $param['logo'] = $this->_logo($param['path'], $html);
        return $param + $this->_lang($param['path']);
    }

    private function _head($path, $html)
    {
        $module = explode('/', $path)[0];
        $file = realpath('./') . '/panel/' . $module . '.css';
        if ($path !== 'main' and file_exists($file)) {
            return str_replace('[M]', $module, $html['css']);
        }
        return '';
    }

    private function _logo($path, $html)
    {
        return $path === 'main' ? $html['logo'] : $html['a-logo'];
    }

    private function _lang($path)
    {
        $file = Path::MODULE . $path . '/lang/' . $this->lang . '.php';
        if (file_exists($file)) {
            $l = require $file;
            !isset($l['lm']) ?: $lang['menu'] = $this->_menu($l['lm']);
            !isset($l['lp']) ?: $lang['lp'] = $l['lp'];
            !isset($l['le']) ?: $this->le = $l['le'];
        }
        return $lang ?? [];
    }

    private function _menu($lm)
    {
        $blank = [];
        if (isset($lm['blank'])) {
            $blank = $lm['blank'];
            unset($lm['blank']);
        }
        asort($lm);
        reset($lm);
        $li = '';
        $html = require 'html/menu.php';
        foreach ($lm as $k => $v) {
            $li_blank = in_array($k, $blank) ? 'li-blank' : 'li';
            $li .= str_replace(['[H]', '[A]'], [$k, $v], $html[$li_blank]);
        }
        return str_replace('[L]', $li, $html['ul']);
    }

    private function _error($code)
    {
        $lang = (require 'lang/error/' . $this->lang . '.php')[$code];
        $error = require Path::HTML . 'error.php';
        parent::view([
            'title' => $lang['title'],
            'content' => str_replace('[E]', $lang['content'], $error)
        ]);
    }

}
