<?php

namespace DomTomProject\EasyRestBundle\Exception;

use Exception;
use Throwable;

/**
 *  Throws if file with rules not found
 * 
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class RulesFileNotFoundException extends Exception {

    public function __construct(string $message = "", int $code = 500, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
