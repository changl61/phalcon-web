<?php

use Phalcon\DI;

class AuthException extends Exception
{
    private static $codes = [401, 403];

    public function __construct($message, $code) {
        if (!in_array($code, self::$codes)) throw new HttpException('The authException code '.$code.' is not allowed', 500);
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__.':['.$this->code.']:'.$this->message.'\n';
    }

    public function __destruct()
    {
        $response = DI::getDefault()->get('response');

        switch ($this->getCode()) {
            case 401: $response->setStatusCode(401, "Unauthorized"); break;
            case 403: $response->setStatusCode(403, "Forbidden"); break;
            default : break;
        }

        $response->sendHeaders();
    }
}