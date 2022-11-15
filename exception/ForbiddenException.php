<?php

namespace mh3yad\phpmvc\exception;

class ForbiddenException extends \Exception
{
   protected $message  ='You aren\'t allowed to access here';
   protected $code = 403;
}