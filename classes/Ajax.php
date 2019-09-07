<?php


class Ajax
{

    private $response;

    public static $SUCCESS_STATUS_CODE = 'Success';
    public static $ERROR_STATUS_CODE = 'Error';

    public static $STATUS_KEY = 'Status';
    public static $RESPONSE_KEY = 'Response';

    function __construct()
    {
        $this->response = array();
    }

    /**
     * Echos a json encoded success response
     * @param $message String | array message to display
     */
    function success($message)
    {
        $this->response[self::$STATUS_KEY] = self::$SUCCESS_STATUS_CODE;
        $this->response[self::$RESPONSE_KEY] = $message;

        echo json_encode($this->response);

    }

    /**
     * Echos a json encoded success response
     * @param $message String | array message to display
     */
    function error($message)
    {
        $this->response[self::$STATUS_KEY] = self::$ERROR_STATUS_CODE;
        $this->response[self::$RESPONSE_KEY] = $message;

        die(json_encode($this->response));
    }


}

?>