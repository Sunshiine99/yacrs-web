<?php

class Response
{
    private $responseID;
    private $response;

    /**
     * @return mixed
     */
    public function getResponseID() {
        return $this->responseID;
    }

    /**
     * @param mixed $responseID
     */
    public function setResponseID($responseID) {
        $this->responseID = $responseID;
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }
}