<?php

namespace DomTomProject\EasyRestBundle\Exception;

use DomTomProject\EasyRestBundle\Exception\Json\JsonException;
use Exception;

class NotFoundHttpJsonException extends JsonException {
    public function __construct($message = "Data not found!", $code = 404, Exception $previous = null) {
        parent::__construct($code, $message, $previous);
    }
}
