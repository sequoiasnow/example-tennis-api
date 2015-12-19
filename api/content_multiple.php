<?php
/**
 * The api base contnet type allowes all preliminary functionality to be
 * built onto an item. It starts with an entire database, or with already
 * existing data and can return it, post it, get it or otherwise perform
 * actions to the data.
 *
 * This allowes the recursive functionality of the API.
 */
class ContentMultiple implements ApiRetrievable {

    /**
     * Implements ApiRetrievable::getAvailableReturns
     *
     * @return array
     */
    public static function getAvailableReturns() {
        return array(
            array(
                'arugment_count' => 2,
                'url_name'       => 'filterby',
                'method_name'    => 'filerBy',
                'request_types'  => array( 'GET' ),
            ),
            array(
                'arugment_count' => 2,
                'url_name'       => 'searchby',
                'method_name'    => 'searchBy',
                'request_types'  => array( 'GET' ),
            ),
            array(
                'argument_count' => 0,
                'url_name'       => 'first',
                'method_name'    => 'first',
                'request_types'  => array( 'GET' ),
            ),
            array(
                'argument_count' => 0,
                'url_name'       => 'last',
                'method_name'    => 'last',
                'request_types'  => array( 'GET' ),
            ),
        );
    }

    /**
     * Implements ApiRetrievable::getMap
     *
     * @return array
     */
    public static function getMap();

    /**
     * Implements ApiRetrievable::create
     *
     * @param array $data
     *
     * @return ApiRetrievable
     */
    public function create( $data ) {
        $contentType = $this->contentType;
        $return      = array();

        foreach ( $this->data as $raw ) {
            $object   = new $contentType( $raw );
            $return[] = $object->create();
        }

        return $return;
    }

    /**
     * Implements ApiRetrievable->get
     *
     * @return array.
     */
    public function get() {
        $contentType = $this->contentType;
        $return      = array();

        foreach ( $this->data as $raw ) {
            $object   = new $contentType( $raw );
            $return[] = $object->update();
        }

        return $return;
    }

    /**
     * Implements ApiRetrievable->update
     *
     * @param array $newValues
     *
     * @return array
     */
    public function update( $newValues ) {
        $contentType = $this->contentType;
        $return      = array();

        foreach ( $this->data as $raw ) {
            $object = new $contentType( $raw );
            $return[] = $object->update();
        }
        return $return;
    }

    /**
     * Implements ApiRetrievable->delete
     *
     * @return array?
     */
    public function delete() {
        $contentType = $this->contentType;
        $return      = array();

        foreach ( $this->data as $raw ) {
            $object = new $contentType( $raw );
            $return[] = $object->delete();
        }
        return $return;
    }

    /**
     * Creates a new instance of the ApiBase class.
     *
     * @param string $contentType.
     * @param array $data
     */
    public function __construct( $contentType, $data ) {
        $this->data =$data;
    }

}
