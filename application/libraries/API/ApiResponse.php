<?php

/**
 * TODO: Headers manipulation could not work with output_buffering = Off in php.ini because
 * they were already sent to the client when I try to set them up.
 */
class ApiResponse {

    private static $httpCodes = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        301 => 'Moved Permanently',
        303 => 'See Other',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error',
    );

    /**
     * Method to send a response (probably with data) to the client. It is intended in most cases to be used with
     * success or redirection response. For error response, just throw an ApiException.
     * @param type $httpStatus      HTTP status code. Default value: 200 (Ok).
     * @param array $data           Associative array with data to be sent to the client (as JSON).
     * @param array $headers        Associative array with ('Header' => 'value') structure to be included in response.
     */
    static function send($httpStatus = 200, Array $data = null, Array $headers = null) {
        /**
         * Propagate current request "callback" query string parameter to new URL if exist Location header
         * in order to be compliant with JSONP
         */
        if (isset($headers['Location']) && $headers['Location'] && isset($_GET['callback']) && $_GET['callback']) {
            $queryString = parse_url($headers['Location'], PHP_URL_QUERY);
            $headers['Location'] .= (($queryString ? '&' : '?') . 'callback=' . $_GET['callback']);
        }

        self::setHeaders($httpStatus, $headers);
        if ($data && is_array($data)) {
            if (isset($_GET['callback']) && $_GET['callback']) {
                echo $_GET['callback'] . '(' . json_encode($data) . ')';
            } else {
                echo json_encode($data);
            }
        }
    }

    /**
     * Helper method to build and send a response from input ApiException instance
     * @param ApiException $ApiException    Api exception which was generated somewhere
     */
    static function sendException(ApiException $ApiException) {
        $response = array();
        if ($ApiException->getCode()) {
            $response['code'] = $ApiException->getCode();
        }
        if ($ApiException->getMessage()) {
            $response['message'] = $ApiException->getMessage();
        }
        if ($response) {
            $type = ApiResponseType::ERROR;
            $response = array_merge(array('type' => $type), $response);
        }
        self::send($ApiException->getHttpStatus(), $response, $ApiException->getHeaders());
    }

    /**
     * Helper method which search in config_reponses.php to find a message with $errorCode as key. Also
     * it format the message using $args
     * @param int $messageCode      Code of the message to be searched
     * @param mixed $messageArgs    Array with arguments to replace message placeholder (%s, %d) using vsprintf().
     *                              If there is only one argument, it could be passed as simple string
     * @return string               The formatted message
     */
    static function getMessage($messageCode, $messageArgs = null) {
        $messages = include 'config_reponses.php';
        if (is_string($messageArgs)) {
            $messageArgs = array($messageArgs);
        }
        if ($messageArgs && is_array($messageArgs)) {
            return vsprintf($messages[$messageCode], $messageArgs);
        }
        return $messages[$messageCode];
    }

    private static function setHeaders($httpStatus, Array $headers = null) {
        $desc = isset(self::$httpCodes[$httpStatus]) ? self::$httpCodes[$httpStatus] : null;
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $httpStatus . ($desc ? ' ' . $desc : $desc));

        // CROS specific headers
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Origin: http://dealpages.local.generalsoftwareinc.com:9002");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        if ($headers && is_array($headers)) {
            /**
             * Processing Location header apart to force the $httpStatus parameter.
             * By default, PHP override any HTTP status code with 302 when Location is passed
             */
//            if (isset($headers['Location'])) {
//                header('Location', true, $httpStatus);
//            }
            
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }
        }
    }

}
