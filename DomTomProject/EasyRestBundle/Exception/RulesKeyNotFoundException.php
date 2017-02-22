<?php

namespace DomTomProject\EasyRestBundle\Exception;

use Exception;

class RulesKeyNotFoundException extends Exception {

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
