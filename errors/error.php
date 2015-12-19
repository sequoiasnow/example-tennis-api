<?php
/**
 * Errors take care of the returning of fatal or uncontainable problems that
 * result from an API request. Typically an error is returned as a JSON object
 * explaining both what it tried to do, where it went wrong and why that
 * went wrong.
 *
 * Errors also should be able to end transactions for mysql, and thus provide
 * callback functionality for the purpose of ending transactions.
 */
class Error {
    /**
     * Instantiates a new instance of the Error class with the arguments
     * indicative of what type of error and a callback to close the transaction.
     *
     * @param array $args
     * @param callable $callback
     */
    public function __construct( $args, $callback = false ) {
        if ( $callback ) {
            call_user_func( $callback );
        }

        // Perform the printing of the error.
        echo json_encode( $args );

        // Perform some cleanup.
        Database::closeConnection();

        // Close the program permenantly.
        exit();
    }
}
