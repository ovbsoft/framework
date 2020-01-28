<?php

namespace Run\panel;

class Route extends core\route\Param {

    use \Run\traits\Filter;

    private $path;

    public function __construct($param)
    {
        $this->_exit($param['path']);
        $this->path = str_replace('/', '\\', $param['path']) . '\\';
        parent::__construct($param);
        isset($this->param['user']['wg']) ? $this->_login() : $this->_404();
    }

    private function _exit($path)
    {
        if ($path === 'logout') {
            $exp = explode('.', $this->server_http_host());
            $exp[0] !== 'www' ?: array_shift($exp);
            $domain = '.' . implode('.', $exp);
            setcookie('panel:hash', '', 0, '/', $domain, true);
            setcookie('panel:user', '', 0, '/', $domain, true);
            header('Location: /');
            exit;
        }
    }

    private function _login()
    {
        new core\login\Login([
            'request' => $this->param['request'],
            'wg' => $this->param['user']['wg']
        ]);
    }

    private function _404()
    {
        $this->error ? $this->_error(404) : $this->_module();
    }

    private function _error($code)
    {
        http_response_code($code);
        new core\main\Main($this->param);
    }

    private function _module()
    {
        $module = '\\Run\\panel\\module\\' . $this->path . $this->class;
        new $module($this->param);
    }

}
