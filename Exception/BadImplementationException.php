<?php

namespace DomTomProject\EasyRestBundle\Exception;

use Throwable;
use Exception;

/**
 * Throws when class must implement something ,but is not
 *
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class BadImplementationException extends Exception {

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
