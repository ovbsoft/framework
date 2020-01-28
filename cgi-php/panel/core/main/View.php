<?php

namespace Run\panel\core\main;

class View extends \Run\data\DateTime {

    private $lt, $exp, $param;
    protected $lang, $le;

    public function __construct($param)
    {
        parent::__construct();
        $this->lt = require 'lang/' . $this->lang . '.php';
        $this->exp = explode('/', $param['path']);
        $this->param = $param;
    }

    protected function view($param = [])
    {
        $view = $this->_view($param);
        echo str_replace(
                array_keys($view),
                array_values($view),
                file_get_contents(__DIR__ . '/view.tpl')
        );
    }

    private function _view($param)
    {
        return[
            '{ LANG }' => $this->lang,
            '{ LT:TITLE }' => $this->lt['title'],
            '{ HEAD }' => $this->param['head'],
            '{ LOGO }' => $this->param['logo'],
            '{ MULTILANG }' => $this->param['multilang'],
            '{ LT:SIGN_OUT-UPP }' => $this->lt['sign_out-upp'],
            '{ TITLE }' => $this->_title($param['title'] ?? false),
            '{ ROUTE }' => $this->_route($param['route'] ?? false),
            '{ CONTENT }' => $param['content'] ?? '',
            '{ REQUEST }' => $this->param['request'],
            '{ MENU }' => $this->param['menu'] ?? '',
            '{ EXT }' => $this->param['ext']
        ];
    }

    private function _title($append)
    {
        $title = '';
        if (isset($this->param['lp']) and ! isset($this->param['error'])) {
            foreach ($this->exp as $v) {
                $title .= ' » ' . $this->param['lp'][$v];
            }
        }
        return $title . ($append ? ' » ' . $append : '');
    }

    private function _route($append)
    {
        $route = '';
        if (isset($this->param['lp']) and ! isset($this->param['error'])) {
            $html = require 'html/route.php';
            $routes = $this->_routes($html);
            !$append ?: $routes .= isset($append['red']) ? (
                            str_replace('[T]', $append['red'], $html['p-red'])
                            ) : (
                            str_replace('[T]', $append, $html['p'])
                            );
            $route = str_replace('[R]', $routes, $html['div']);
        }
        return $route;
    }

    private function _routes($html)
    {
        $routes = '';
        for ($i = 0, $c = count($this->exp), $path = ''; $i < $c; $i++) {
            if ($i === $c - 1) {
                $routes .= str_replace(
                        '[T]', $this->param['lp'][$this->exp[$i]], $html['p']
                );
                break;
            }
            $search = ['[H]', '[A]'];
            $path .= '/' . $this->exp[$i];
            $replace = [$path, $this->param['lp'][$this->exp[$i]]];
            $routes .= str_replace($search, $replace, $html['a']);
        }
        return $routes;
    }

}
