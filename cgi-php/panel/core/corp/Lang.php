<?php

namespace Run\panel\core\corp;

use Run\panel\core\corp\Path;

class Lang {

    use \Run\traits\Filter;

    private $langs;
    public $lang;

    public function __construct()
    {
        $this->lang = $this->_lang();
    }

    private function _lang()
    {
        $file = Path::SZ . 'panel.langs.sz';
        $this->langs = unserialize(file_get_contents($file));
        $lang = $this->langs['lang'];
        if (!$this->langs['multilang']) {
            return $lang;
        }
        if (filter_has_var(0, 'panel:lang')) {
            $post = filter_input(0, 'panel:lang');
            if (isset($this->langs['langs'][$post])) {
                $this->_cookie($post);
                $lang = $post;
            }
        } elseif (filter_has_var(2, 'panel:lang')) {
            $cookie = filter_input(2, 'panel:lang');
            !isset($this->langs['langs'][$cookie]) ?: $lang = $cookie;
        }
        return $lang;
    }

    private function _cookie($post)
    {
        $year = strtotime('+ 1 year');
        $exp = explode('.', $this->server_http_host());
        $exp[0] !== 'www' ?: array_shift($exp);
        $domain = '.' . implode('.', $exp);
        setcookie('panel:lang', $post, $year, '/', $domain, true);
    }

    public function multilang()
    {
        if (!$this->langs['multilang']) {
            return '';
        }
        $html = require Path::HTML . 'lang.php';
        $button = '';
        foreach ($this->langs['langs'] as $k => $v) {
            if ($k !== $this->lang) {
                $button .= str_replace(
                        ['[V]', '[L]', '[B]'], [$k, $k, $v], $html['button']
                );
            }
        }
        return str_replace(
                ['[L]', '[B]'],
                [
                    $this->langs['langs'][$this->lang],
                    $this->_hidden($html) . $button
                ],
                $html['div']
        );
    }

    private function _hidden($html)
    {
        $hidden = '';
        $post = filter_input_array(0);
        if (!empty($post)) {
            foreach ($post as $k => $v) {
                if ($k === 'panel:lang') {
                    continue;
                }
                $hidden .= ($k === 'login' or $k === 'post') ?
                        str_replace('[N]', $k, $html['hidden']) :
                        str_replace(
                                ['[N]', '[V]'], [$k, $v], $html['hidden-value']
                );
            }
        }
        return $hidden;
    }

}
