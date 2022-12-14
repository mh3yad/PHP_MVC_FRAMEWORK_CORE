<?php

namespace mh3yad\phpmvc;

class Response
{
    public function setStatusCode(int $code): int
    {
       return http_response_code($code);
    }

    public function redirect(string $path): void
    {
        header("location: $path");
    }
}