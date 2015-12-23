<?php
class Member extends ContentType {
    /**
     * Implements ApiRetrievable::getInstance
     *
     * @param int $id
     *
     * @return array
     */
    public static function getInstance( $id ) {
        $result = Database::query( "SELECT * FROM members WHERE id=$id" );
        $data = $result->fetch_assoc();
        $result->close();

        return new self( $result );
    }

    /**
     * Implements ApiRetrievable::getAllInstances
     *
     * @return array
     */
    public static function getAllInstances() {
        $result = Database::query( "SELECT * FROM members" );
        $data = array();

        while ( $row = $result->fetch_assoc() ) {
            $data[] = new self( $row );
        }

        $result->close();

        return $data;
    }

    /**
     * Implements ApiRetrievable::getAvailableReturns
     *
     * @return array
     */
    public static function getAvailableReturns() {
        return array();
    }

    /**
     * Implements ApiRetrievable::getMap
     *
     * @return array
     */
    public static function getMap() {
        return array();
    }

    /**
     * Implements ApiRetrievable::create
     *
     * @return ApiRetrievable
     */
    public function create() {

    }

    /**
     * Implements ApiRetrievable->get
     *
     * @return array.
     */
    public function get() {
        return $this;
    }

    /**
     * Implements ApiRetrievable->update
     *
     * @param array $newValues
     *
     * @return array
     */
    public function update( $newValues ) {
        // ...
    }

    /**
     * Implements ApiRetrievable->delete
     *
     * @return array?
     */
    public function delete() {
        // ...
    }

}
