<?php

namespace DomTomProject\EasyRestBundle\Exception;

use Exception;
use Throwable;

/**
 * 
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class BadFilenameTypeException extends Exception {

    public function __construct(string $message = "", int $code = 500, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
