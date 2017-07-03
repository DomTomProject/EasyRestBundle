<?php

namespace DomTomProject\EasyRestBundle\Exception;

use DomTomProject\EasyRestBundle\Exception\Json\JsonException;
use Exception;

/**
 *  Throws if file with rules not found
 *
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class BadRequestHttpJsonException extends JsonException
{
    public function __construct($message = "Bad request data", $code = 400, Exception $previous = null) {
        parent::__construct($code, $message, $previous);
    }
}