<?php

namespace app\core\exception;

class NotFoundException extends \Exception
{
    protected $message  ='This page not found';
    protected $code = 404;

}