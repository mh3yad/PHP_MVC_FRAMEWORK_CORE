<?php

namespace app\core;

class Request
{
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet():bool{
        return $this->method() === 'get';
    }

    public function isPost():bool{
        return $this->method() === 'post';
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? false;
        $str_pos = strpos($path, '?');
        if ($str_pos) {
            return substr($path, 0, $str_pos);
        }
        return $path;
    }

    public function getBody():array
    {
        $body = [];
        if ($this->method() == 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        } else {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }



}