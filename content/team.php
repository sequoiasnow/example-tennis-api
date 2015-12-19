<?php
abstract class ContentType {
    public static function getAvailableReturns() {
        return array(
            'members'
            'searchmembers' => array(
                'arg_count' => 2 // api/team/1/searchmembers/name/john%20snow
            )
        );
    }

    public static function getAuthorization() {
        return array(
            'get'     => Authorization::Guest,
            'members' => Authorization::Guest,
            'put'     => Authorization::Captian,
            'post'    => Authorization::Captian,
            'delete'  => Authorization::Captain,
        )
    }

    public static function getMap() {
        return array(
            'id'   => 'id',
            'name' => 'user_name'
        )
    }

    public function update() {
        Database::query( "UPDATE ... WHERE id=$this->id" );
    }

    public function create();

    public function delete();

    // One of the args is a filter array.
    public function get();

    public function members() {
        // get member ids...
        $ids     = array();
        $members = array();

        foreach ( $ids as $id ) {
            $members[] = new Api( "/members/$id", 'GET' );
        }

        return $members;
    };

    public function searchMembers( $key, $what ) {

    }
}
