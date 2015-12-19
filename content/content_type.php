<?php
abstract class ContentType {
    public static function getAvailableReturns();

    public static function getAuthorization();

    public function update();

    public function create();

    public function delete();

    public function get();

    public function __construct( $args ) {
        foreach ( $args as $key => $arg ) {
            $this->$key = $arg;
        }
    }
}
