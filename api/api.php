<?php

class Api {
    /**
     * Returns the request type based off of the SERVER variable.
     *
     * @return string
     */
    private function getRequestType() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Gets the data from the request, assumes already instantiated
     * requestType.
     *
     * @return array
     */
    private function getRequestData() {
        $data = array();
        switch ( $this->requestType ) {
            case 'PUT':
                parse_str( file_get_contents( 'php://input' ), $data );
                break;
            case 'POST':
                $data = $_POST;
                break;
            case 'DELETE':
                $data = $this->path;
                break;
            case 'GET':
                $data = $_GET;
                break;
        }

        return $data;
    }

    /**
     * Processes the url of the path for easy splitting and returning.
     *
     * @return array
     */
    private function getPathComponents() {
        return explode( '/', $this->path );
    }

    /**
     * Getds data from the path components as to what is currently being
     * queried.
     *
     * @return array.
     */
    private function getPathData() {

    }


    public function __construct( $path, $requestType = 0, $requestData = 0 ) {
        // Establish the $path.
        $this->path = $path;

        // Establish the request type
        $this->requestType = $requestType ?: $this->getRequestType();

        // Establish the request data
        $this->requestData = $requestData ?: $this->getRequestData();

        // Establish information from the path.
        $this->pathComponents = $this->getPathComponents();
        $this->pathData       = $this->getPathData();

    }
}
