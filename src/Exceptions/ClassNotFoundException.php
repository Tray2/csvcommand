<?php

namespace Tray2\MakeSeeder\Exceptions;

use Exception;

class ClassNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}