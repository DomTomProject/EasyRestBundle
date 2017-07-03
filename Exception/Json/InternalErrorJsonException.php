<?php

namespace DomTomProject\EasyRestBundle\Exception;

use DomTomProject\EasyRestBundle\Exception\Json\JsonException;
use Exception;

class InternalErrorJsonException extends JsonException {

    public function __construct($message = "Internal error!", $code = 500, Exception $previous = null) {
        parent::__construct($code, $message, $previous);
    }

}
