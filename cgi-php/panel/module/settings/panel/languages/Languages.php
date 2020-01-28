<?php

namespace Run\panel\module\settings\panel\languages;

use Run\panel\core\corp\Path;

class Languages extends \Run\panel\core\main\Main {

    use \Run\panel\core\traits\table,
        \Run\traits\Filter;

    private $ext, $file, $langs;

    public function __construct($param)
    {
        $this->ext = $param['ext'];
        parent::__construct($param);
        $this->file = Path::SZ . 'panel.langs.sz';
        $this->langs = unserialize(file_get_contents($this->file));
        !filter_has_var(0, 'post') ?: $this->_post();
        parent::view([
            'content' => $this->_content()
        ]);
    }

    private function _post()
    {
        $lang = filter_input(0, 'lang');
        if ($this->_post_multilang() or $this->langs['lang'] !== $lang) {
            if (isset($this->langs['langs'][$lang])) {
                $this->langs['lang'] = $lang;
            }
            $this->_write(serialize($this->langs));
        }
    }

    private function _write($sz)
    {
        if (boolval(file_put_contents($this->file, $sz))) {
            header('Location: /settings/panel' . $this->ext);
            exit;
        }
    }

    private function _post_multilang()
    {
        $multilang = boolval(filter_input(0, 'multilang'));
        if ($this->langs['multilang'] !== $multilang) {
            $exp = explode('.', $this->server_http_host());
            $exp[0] !== 'www' ?: array_shift($exp);
            $domain = '.' . implode('.', $exp);
            if ($multilang) {
                $lang = $this->langs['lang'];
                setcookie('panel:lang', $lang, 0, '/', $domain, true);
            } else {
                setcookie('panel:lang', '', 0, '/', $domain, true);
            }
            $this->langs['multilang'] = $multilang;
            return true;
        }
        return false;
    }

    private function _content()
    {
        $this->table = require Path::FORM . 'table.php';
        return $this->table_button(
                        '',
                        '',
                        $this->table_solid_colon(
                                $this->le['multilang'],
                                $this->_multilang()
                        ) . $this->table_double_colon(
                                $this->le['lang'],
                                $this->_lang()
        ));
    }

    private function _multilang()
    {
        $yes = $this->langs['multilang'] ? ' checked' : '';
        $no = $this->langs['multilang'] ? '' : ' checked';
        return $this->table_boolean(
                        'multilang',
                        $yes,
                        $this->le['yes'],
                        $no,
                        $this->le['no'],
        );
    }

    private function _lang()
    {
        return $this->table_select_key(
                        $this->langs['langs'],
                        $this->langs['lang'],
                        'lang'
        );
    }

}
