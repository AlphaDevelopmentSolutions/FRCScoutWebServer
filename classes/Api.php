<?php

class Api
{
    private $response;

    private $SUCCESS_STATUS_CODE = 'Success';
    private $ERROR_STATUS_CODE = 'Error';

    private $STATUS_KEY = 'Status';
    private $RESPONSE_KEY = 'Response';

    function __construct()
    {
        $this->response = array();
    }

    /**
     * Echos a json encoded success response
     * @param $message string | array message to display
     */
    function success($message)
    {
        $this->response[$this->STATUS_KEY] = $this->SUCCESS_STATUS_CODE;
        $this->response[$this->RESPONSE_KEY] = $message;

        echo die(json_encode($this->response));

    }

    /**
     * Echos a json encoded success response
     * @param $message String | array message to display
     */
    function error($message)
    {
        $this->response[$this->STATUS_KEY] = $this->ERROR_STATUS_CODE;
        $this->response[$this->RESPONSE_KEY] = $message;

        echo die(json_encode($this->response));
    }

    /**
     * Returns if the key was valid when initializing the API
     * @param $key string API key from web server
     * @return boolean
     */
    public function isKeyValid($key)
    {
        return $key == API_KEY;
    }


}

?>