<?php

namespace Run;

class Branch {

    use \Run\traits\Filter;

    private $query, $ext, $param, $pattern = '/^[\w\-\.\/\?\&\=\:]+$/iu';

    public function __construct()
    {
        new data\sz\Data;
        $run = unserialize(file_get_contents(Root::SZ . 'branch.run.sz'));
        $request = $this->server_request_uri();
        if ($run['admin']) {
            $this->ext = $run['ext'];
            $this->_branch($this->_request($request));
        } else {
            new data\db\install\Install($request);
        }
    }

    private function _request($request)
    {
        $this->param['request'] = $request;
        $this->query = strrchr($this->param['request'], '?');
        $urn = $this->query ? (
                substr($this->param['request'], 0, - strlen($this->query))
                ) : $this->param['request'];
        $ext = strrchr($urn, '.');
        $path = $ext ? substr($urn, 1, - strlen($ext)) : substr($urn, 1);
        $this->param['path'] = empty($path) ? false : $path;
        $this->param['ext'] = $ext;
        $this->param['error'] = $this->_error();
    }

    private function _error()
    {
        return (
                preg_match($this->pattern, $this->param['request']) === 0 or
                preg_match('/\/\//', $this->param['request']) === 1 or
                preg_match('/[\/]$/', $this->param['path']) === 1 or
                $this->param['ext'] and empty($this->param['path'])
                ) ? true : false;
    }

    private function _branch()
    {
        $branch = $this->ext === $this->param['ext'];
        $branch ? $this->_panel() : $this->_website();
    }

    private function _panel()
    {
        new panel\Route($this->param);
    }

    private function _website()
    {
        new website\Route($this->param + [
            'query' => boolval($this->query) ? $this->query : '',
            'panel' => $this->ext
        ]);
    }

}
