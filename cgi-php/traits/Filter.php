<?php

namespace Run\traits;

trait Filter {

    protected function server_http_host()
    {
        return urldecode(filter_var($_SERVER['HTTP_HOST']));
    }

    protected function server_request_uri()
    {
        return urldecode(filter_var($_SERVER['REQUEST_URI']));
    }

    protected function server_protocol()
    {
        return filter_var($_SERVER['SERVER_PROTOCOL']);
    }

    protected function server_user_agent()
    {
        return filter_var($_SERVER['HTTP_USER_AGENT']);
    }

}
