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
     * Checks for a content tpye and returns it based off of the action being
     * perpetrated for the API.
     *
     * @return string
     */
    private function establishContentType() {
        if ( isset( $this->pathComponents[0] ) ) {
            $contentType = $this->pathComponents[0];

            if ( ! class_exists( $contentType ) ) {
                new Error( array(
                    'type'     => Error::Fatal,
                    'message' => 'Content Type not found or does not exist.',
                ) );
            }

            // Return the content type.
            return $contentType;
        } else {
            new Error( array(
                'type'    => Error::Fatal,
                'message' => 'No Content Type Given.',
            ) );
        }
    }

    /**
     * Establish what is being queried as a set of data.
     *
     * @return array
     */
    private function getInitialSet() {
        $object = null;
        $contentType = $this->contentType;


        if ( isset( $this->pathComponents[1] ) &&
             is_numeric( $this->pathComponents[1] )) {
            // Querying for a specific instance, not a set.
            $id     = $this->pathComponents[1];
            $object = $contentType::getInstance( $id );

            // Set the curretn pointer to the number of components used.
            $this->componentIndex = 2;
        } else {
            // There are multiple possible objects, and thus a set must exist.
            $object = new ApiDataSet( $contentType );

            // Set the curretn pointer to the number of components used.
            $this->componentIndex = 1;
        }

        return $object;
    }

    /**
     * Processes the initial data set, recursivly in order to achieve a
     * fully functional api call.
     *
     * @return
     */
    private function processSet( ApiRetrievable $set ) {
        // Checks if the next index is available in the set.
        if ( ! isset( $this->pathComponents[ $this->componentIndex ] ) ) {
            // Is finnal query, call request type method.
            return call_user_method( $this->requestType,
                                     $set,
                                     $this->requestData );
        }

        $compVal = $this->pathComponents[ $this->componentIndex ];

        // Check if is actually searchin for an id.
        if ( is_numeric( $compVal ) ) {
            $contentType = get_class( $set );
            if ( get_class( $set )  == 'ApiDataSet' ) {
                $contentType = $set->contentType;
            }

            return $this->processSet( $contentType::getInstance( $compVal ) );
        }

        // Call the acceptable functions.
        $funcs     = $set::getAvailableReturns();
        $usedFunc  = null;

        // Check if a function is being called...
        foreach ( $funcs as $func ) {
            if ( $compVal == $func[ 'url_name' ] ) {
                $usedFunc = $func;
            }
        }

        // Check if the function exists.
        if ( $usedFunc ) {
            $method    = $usedFunc['method_name'];
            $arguments = array();

            // Get the arguments from the array.
            $iterations = 0;

            // First iteration throuh the component index is moved to the
            // next argument.
            $this->componentIndex++;
            while ( $this->componentIndex < count( $this->pathComponents ) &&
                    $iterations++ < $usedFunc[ 'argument_count' ] ) {
                $arguments[] = $this->pathComponents[$this->componentIndex++];
            }


            $result = call_user_method_array( $method, $set, $arguments );

            // Establish what type of result is being expected.
            $returnType = isset( $userFunc['return_type'] ) ?
                          $userFunc['return_type'] : ':self';

            // Check for ':self'
            if ( $returnType == ':self' ) {
                // Check if this is a set or a result.
                if ( get_class( $set ) == 'ApiDataSet' ) {
                    $returnType = $set->contentType;
                } else {
                    $returnType = get_class( $set );
                }
            }

            if ( isset( $usedFunc['return_set'] ) &&
                 ! $usedFunc['return_set'] ) {
                // Return an instance of the return type not a set.
                return $this->processSet(
                    new $returnType( $result )
                );
            }

            // Turn the result into a new data set. Send this set to be
            // processed.
            return $this->processSet(
                new ApiDataSet( $returnType, $result )
            );
        } else {
            return call_user_method( $this->requestType,
                                     $set,
                                     $this->requestData );
        }
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
        $this->contentType    = $this->establishContentType();
        $this->initialSet     = $this->getInitialSet();

        // Recursibly process the set.
        $this->returnData = $this->processSet( $this->initialSet );
    }

    /**
     * Print out the return data as a string of json, if it is an array. If not
     * simply print out the result.
     *
     * @return string
     */
    public function __toString() {
        if ( is_string( $this->returnData ) ) {
            return $this->returnData;
        }

        return json_encode( $this->returnData );
    }
}
