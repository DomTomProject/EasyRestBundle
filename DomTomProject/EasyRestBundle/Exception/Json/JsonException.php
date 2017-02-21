<?php

namespace DomTomProject\EasyRestBundle\Exception\Json;

use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonException extends HttpException {

    public function __construct($code = 0, $message = "", Exception $previous = null) {
        $messageJson = json_encode(['message' => $message, 'code' => $code]);
        parent::__construct($code, $messageJson, $previous);
    }

}
