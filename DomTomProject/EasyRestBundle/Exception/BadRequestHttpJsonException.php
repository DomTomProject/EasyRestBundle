<?php

namespace DomTomProject\EasyRestBundle\Exception;

use DomTomProject\EasyRestBundle\Exception\Json\JsonException;
use Exception;

class BadRequestHttpJsonException extends JsonException
{
    public function __construct($message = "", $code = 400, Exception $previous = null) {
        parent::__construct($code, $message, $previous);
    }
}