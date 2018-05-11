<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * ApiException holds exception data and response data. You can generate ApiException anywhere:
 *  throw new ApiException(take a look to constructor parameters)
 * 
 * To send the ApiException as response to client you must use ApiResponse::sendException($apiException) in try-catch block.
 * Most cases the try-catch would be in main index.php:
 * try {
 *      //whatever code
 * } catch (ApiException $exc) {
 *      ApiResponse::sendException($exc);
 * } catch (Exception $exc) {
 *      //send 500 Internal Server Error
 * }
 *
 * @author Delmo Heredia
 */
class ApiException extends Exception {
	
    private $httpStatus;
//    private $errorCode;
    private $headers;

    /**
     * 
     * @param type $httpStatus      HTTP status code. Default value: 500 (Internal Server Error).
     * @param type $errorCode       Application error code. If no value (0 or null), no error code will be included
     *                              in response.
     * @param type $message         Exception message. If no value ('' or null) but $errorCode, the message will be
     *                              searched in config_responses.php (through ApiResponse::getMessage()). If finally
     *                              no message was found, it won't be included in response.
     * @param mixed $messageArgs    Array with arguments to replace message placeholder (%s, %d) using vsprintf().
     *                              If there is only one argument, it could be passed as simple string
     * @param array $headers        Associative array with ('Header' => 'value') structure to be included in response.
     */
    public function __construct($httpStatus = 500, $errorCode = null, $message = null, $messageArgs = null, $headers = null) {
            if (!$message && $errorCode) {
                    $message = ApiResponse::getMessage($errorCode, $messageArgs);
            }
            parent::__construct($message, $errorCode, null);
            if (is_array($headers)) {$this->headers = $headers;}
            $this->httpStatus = $httpStatus;
            $this->errorCode = $errorCode;
    }

    public function getHeaders() {
            return $this->headers;
    }

    public function getHttpStatus() {
            return $this->httpStatus;
    }

    public function getErrorCode() {
            return $this->errorCode;
    }
}
