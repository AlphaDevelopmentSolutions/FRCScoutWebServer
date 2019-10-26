<?php

class Api
{
    public $ACTION_KEY = 'Action';
    public $API_KEY = 'ApiKey';

    /**
     * Echos a json encoded success response
     * @param $message string | array message to display
     */
    function success($message)
    {
        header("HTTP/1.0 200");
        $this->respond($message);
    }

    /**
     * Echos a json encoded response
     * @param $message String | array message to display
     */
    function error($message)
    {
        header("HTTP/1.0 500");
        $this->respond($message);
    }

    /**
     * Echos a json encoded response
     * @param $message string | array message to display
     */
    function unauthorized($message)
    {
        header("HTTP/1.0 401");
        $this->respond($message);
    }

    /**
     * Echos a json encoded response
     * @param $message string | array message to display
     */
    function forbidden($message)
    {
        header("HTTP/1.0 403");
        $this->respond($message);
    }

    /**
     * Echos a json encoded response
     * @param $message string | array message to display
     */
    function notImplemented($message)
    {
        header("HTTP/1.0 501");
        $this->respond($message);
    }

    /**
     * Echos a json encoded response
     * @param $message string | array message to display
     */
    function badRequest($message)
    {
        header("HTTP/1.0 400");
        $this->respond($message);
    }

    /**
     * Prints message to screen
     * @param $message
     */
    private function respond($message)
    {
        die(json_encode(is_array($message) ? $message : [$message], JSON_PRETTY_PRINT));
    }
}

?>