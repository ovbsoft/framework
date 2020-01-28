<?php

namespace Run\website\core\type\main;

class Main extends \Run\website\view\base\View {

    use \Run\traits\Filter;

    private $error, $le;

    public function __construct($param)
    {
        parent::view($this->_param($param));
    }

    private function _param($param)
    {
        $this->error = false;
        $this->html = require 'html.php';
        $this->lang = $this->_lang();
        $this->le = require 'lang/' . $this->lang . '.php';
        $error = ($this->error or $param['error']) ? true : false;
        $param['request'] = $this->_logo($param['request']);
        $param['content'] = $error ? $this->_error() : $this->_content();
        return $param;
    }

    private function _lang()
    {
        return 'ru';
    }

    private function _error()
    {
        header($this->server_protocol() . ' 404 Not Found');
        return str_replace('[E]', $this->le['error'], $this->html['id-error']);
    }

    private function _logo($request)
    {
        return $request === '/' ? (
                $this->html['logo']['div']
                ) : $this->html['logo']['a-div'];
    }

    private function _content()
    {
        $main = [
            '{ PAGE }' => $this->le['page']
        ];
        return str_replace(
                array_keys($main),
                array_values($main),
                file_get_contents(__DIR__ . '/main.tpl')
        );
    }

}
