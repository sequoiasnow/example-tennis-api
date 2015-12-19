<?php
abstract class ContentType implements ApiRetrievable {
    public function __construct( $args ) {
        foreach ( $args as $key => $arg ) {
            $this->$key = $arg;
        }
    }
}
