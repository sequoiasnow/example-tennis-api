<?php
/**
 * The api data set type allowes all preliminary functionality to be
 * built onto an item. It starts with an entire database, or with already
 * existing data and can return it, post it, get it or otherwise perform
 * actions to the data.
 *
 * This allowes the recursive functionality of the API.
 */
class ApiDataSet implements ApiRetrievable {
    /**
     * Implements ApiRetrievable::getInstance
     *
     * @param int $id
     *
     * @return array
     */
    public static function getInstance( $id ) {
        return null;
    }

    /**
     * Implements ApiRetrievable::getAllInstances
     *
     * @return array
     */
    public static function getAllInstances() {
        return array();
    }

    /**
     * Implements ApiRetrievable::getAvailableReturns
     *
     * @return array
     */
    public static function getAvailableReturns() {
        return array(
            array(
                'arugment_count' => 3,
                'url_name'       => 'filterby',
                'method_name'    => 'filerBy',
                'request_types'  => array( 'GET' ),
                'return_type'    => ':self',
            ),
            array(
                'argument_count' => 0,
                'url_name'       => 'first',
                'method_name'    => 'first',
                'request_types'  => array( 'GET' ),
                'return_type'    => ':self',
            ),
            array(
                'argument_count' => 0,
                'url_name'       => 'last',
                'method_name'    => 'last',
                'request_types'  => array( 'GET' ),
                'return_type'    => ':self',
            ),
            array(
                'argument_count' => 2,
                'url_name'       => 'orderby',
                'method_name'    => 'orderBy',
                'request_types'  => array( 'GET' ),
                'return_type'    => ':self',
            )
        );
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

    public $contentType;
    public $data;

    /**
     * Creates a new instance of the ApiBase class.
     *
     * @param string $contentType.
     * @param array $data
     */
    public function __construct( $contentType, $data = 0 ) {
        $this->contentType = $contentType;

        // Get all instances of a content type if no data is provided.
        if ( $data ) {
            $this->data = $data;
        } else {
            $this->data = $contentType::getAllInstances();
        }
    }

    /**
     * Filter an array based off of a request from the url which provides three
     * arguments.
     *
     * @param string $what
     * @param string $how
     * @param string $where
     *
     * @return ContentMultiple
     */
    public function filerBy( $what, $how, $where ) {
        $fullData = $this->data;
        $finnal   = array();

        // Iterate through to look for viable instances.
        foreach ( $fullData as $instance ) {
            if ( isset( $instance[$what] ) ) {
                // Check the where conditions.
                switch ( $how ) {
                    case '=':
                        if ( $instance[$what] == $where ) {
                            $finnal[] = $instance;
                        }
                        break;
                }
            }
        }

        return $finnal;
    }

    /**
     * Orders the resuts based off a variety of parameters.
     *
     * @param string $what
     * @param string $how
     *
     * @return array
     */
    public function orderBy( $what, $how ) {
        return uasort( $this->data, function( $af, $bf ) {
            if ( ! isset( $af[$what] ) ) { return -1 * $order; }

            if ( ! isset( $bf[$what] ) ) { return 1 * $order; }


            $a = $af[$what];
            $b = $bf[$what];

            if ( $a == $b ) { return 0; }

            return $order * ( $a < $b ? -1 : 1 );
        });
    }

    /**
     * Get the first instance of the search.
     *
     * @return array
     */
    public function first() {
        return $this->data[0];
    }

    /**
     * Get the last instance of the search.
     *
     * @return array
     */
    public function last() {
        return $this->data[count($this->dataw) - 1];
    }
}
