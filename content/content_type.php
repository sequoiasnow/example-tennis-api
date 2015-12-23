<?php
abstract class ContentType implements ApiRetrievable {
    /**
     * Returns an isntance of the given content type from an id. This provides
     * for unique mysql JOIN functionality.
     *
     * @param int $id
     *
     * @return self
     */
    abstract public static function getInstance( $id );

    public function __construct( $args ) {
        foreach ( $args as $key => $arg ) {
            $this->$key = $arg;
        }
    }
}
