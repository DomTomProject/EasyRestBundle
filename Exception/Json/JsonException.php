<?php

namespace DomTomProject\EasyRestBundle\Exception\Json;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Base Exception for json exceptions
 * 
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class JsonException extends HttpException {

    public function __construct($code = 0, $message = "", Exception $previous = null) {
        $messageJson = json_encode(['message' => $message, 'code' => $code]);
        parent::__construct($code, $messageJson, $previous);
    }

}
