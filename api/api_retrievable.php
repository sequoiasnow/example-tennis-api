<?php
/**
 * Determines all the necessary methods an ContentType must comply to in
 * order to be retrievable by the API
 */
interface ApiRetrievable {
    /**
     * Determines the possibility of a variety of funcitonality for the
     * return of differing data.
     *
     * Should return an array of function defintions with the following
     * parameters:
     *    -- argument_count: Number of arguments accepted by the funciton,
     *                       this translates to items followed by slashes after
     *                       the functions declaration
     *    -- url_name      : The name of the function in the url.
     *    -- method_name   : The name of the function as a method in the class.
     *    -- request_types : The types of request ( GET, POST, PUT, DELETE )
     *                       that are acceptable for the use of the function.
     *                       The request type is the last argument passed to
     *                       the function.
     *
     * @return array
     */
    public static function getAvailableReturns();



    /**
     * Returns an instance based off of an id. The id is the one common
     * demoninator among all Content Types.
     *
     * @param int $id
     *
     * @return self
     */
    public static function getInstance( $id );

    /**
     * Returns all instances of self. Will return an array of instantiated
     * instances of self. This functionality is largelly limited to the
     * ApiDataSet retreival of multiple content types, though it can be
     * otherwise employeed.
     *
     * @return [self]
     */
    public static function getAllInstances();

    /**
     * Returns the authorization level of the various methods accessible through
     * the api.
     *
     * Returns an array of method name and authorization value.
     *
     * @return array
     */
    // public static function getAuthorization();

    /**
     * Returns a key valued pair array of the database column names to the
     * property names of the object. Used to maintain compatibility and allow
     * for database updates.
     *
     * @return array
     */
    public static function getMap();

    /**
     * Creates a new instance of the current content type and saves it to the
     * database. It then returns the newly created instance with and id from
     * the database.
     *
     * It is notable that, as the data is more inclusive than the bare
     * minimum ot delcare a instance, it may also involve the creation, or
     * linking of other content types in order to fully involve the process.
     *
     * @return ApiRetrievable
     */
    public function create();

    /**
     * As the functionality of a search is carried out by the parent method,
     * this is simply the process of returning an array of information, or an
     * object, that will be sent back to the user as the result of a GET
     * request.
     *
     * @return array.
     */
    public function get();

    /**
     * Updates the current state of the instance with newer values, without
     * altering the id.
     *
     * This method allowes for the updating of other Content Types outside
     * of this class, though that is not always the case, and would tend
     * to use their respective update methods.
     *
     * @param array $newValues
     *
     * @return array
     */
    public function update( $newValues );

    /**
     * Performs a delete of the current item from the database. This may also
     * involve unlinking other referenced items from the database. Can return
     * an array if needed but is assumed ok unless an error is returned.
     *
     * @return array?
     */
    public function delete();
}
