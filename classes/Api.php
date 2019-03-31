<?php


class Api
{

    private $response;

    private $SUCCESS_STATUS_CODE = 'Success';
    private $ERROR_STATUS_CODE = 'Error';

    private $STATUS_KEY = 'Status';
    private $RESPONSE_KEY = 'Response';

    function __construct($key)
    {
        if($key != API_KEY)
        {
            $this->error('Invalid Key');
            die();
        }

        $this->response = array();
    }

    /**
     * Echos a json encoded success response
     * @param $message message to display
     */
    function success($message)
    {
        $this->response[$this->STATUS_KEY] = $this->SUCCESS_STATUS_CODE;
        $this->response[$this->RESPONSE_KEY] = $message;

        echo json_encode($this->response);

    }

    /**
     * Echos a json encoded success response
     * @param $message message to display
     */
    function error($message)
    {
        $this->response[$this->STATUS_KEY] = $this->ERROR_STATUS_CODE;
        $this->response[$this->RESPONSE_KEY] = $message;

        echo json_encode($this->response);

    }
}

?>