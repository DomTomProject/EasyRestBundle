<?php

namespace DomTomProject\EasyRestBundle\Exception;

use Exception;

/**
 *  Throws if key of set of rules not found
 * 
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class RulesKeyNotFoundException extends Exception {

    public function __construct(string $message = "", int $code = 500, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
