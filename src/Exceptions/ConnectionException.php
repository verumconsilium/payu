<?php

namespace VerumConsilium\PayU\Exception;

use Exception;

/**
 *
 * Payu exception throw when the Api service can't
 * connect with the server
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0
 *
 */
class ConnectionException extends Exception
{
    public $payUCode;

    /**
     * constructor method
     * @param string $payuCode a element of PayUErrorCodes
     * @param string $message the message for this exception
     * @param int $code the code for this exception
     * @param string $previous if exist a previous exception
     */
    public function __construct($payuCode, $message, $code = null, $previous = null)
    {
        $this->payUCode = $payuCode;
        parent::__construct($message, $code, $previous);
    }
}
