<?php
/**
 * Define's a class that uses static methods to allow for a global namespace
 * for the use of a database.
 */
class Database {
    // The connection for the database.
    private static $connection;

    /**
     * Opens a new database connection with parameters from config.php
     */
    private static function openConnection() {
        self::$connection = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

        if ( self::$connection->connect_errno ) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        return self::$connection;
    }

    /**
     * Closses the connection to the database... use openConnection() to renew.
     */
    protected static function closeConnection() {
        self::$connection->close();
        self::$conneciton = false;
    }

    /**
     * Returns the connection and opens it if is necessary.
     *
     * @return mysqli.
     */
    protected static function getConnection() {
        if ( ! self::$connection ) {
            return self::openConnection();
        }
        return self::$connection;
     }

    /**
     * Escapes a string of special mysqli parameters.
     *
     * @param string... $string
     *
     * @return stirng/array
     */
    public static function escapeString() {
        // Get the connection to escape the string.
        $connection = self::getConnection();

        // Gather the arguments for the current function as an array.
        $args = func_get_args();

        // Check if the function is simply single use
        if ( count( $args ) === 1 ) {
            return $connection->real_escape_string( $args[0] );
        }

        // Construct an array of the arguments escaped values.
        $resultArray = array();

        foreach ( $args as $arg ) {
            $resultArray[] = $connection->real_escape_string( $arg );
        }

        return $resultArray;
    }

    /**
     * Turns an associative array into an array of format col=val.
     *
     * @param array $array
     *
     * @return array
     */
    protected static function makeArrayColVal( $array ) {
        return array_map( function( $k, $v ) {
            return "`$k`='$v'";
        }, array_keys( $array ), array_values( $array ) );
    }

    /**
     * Returns the table name with a prefix.
     *
     * @param string $name
     *
     * @return string
     */
    protected static function getTableName( $name ) {
        return  $name;
    }

    /**
     * Filter out all emptys from the array.
     *
     * @param array $array
     *
     * @return array
     */
    protected static function filterEmptyArray( $array ) {
        $final = array();
        foreach ( $array as $key => $val ) {
            if ( $val ) {
                $final[$key] = $val;
            }
        }
        return $final;
    }

    /**
     * Adds quots to every value in an array.
     *
     * @param array $array
     * @param char $quote
     *
     * @return array
     */
    protected static function addQuotes( $array, $quote ) {
        foreach ( $array as &$val ) {
            $val = $quote . $val . $quote;
        }
        return $array;
    }

    /**
     * Perform a mysqli query.
     *
     * @param string $query
     *
     * @return mysqli::query
     */
    public static function query( $statement ) {
        $connection = self::getConnection();
        return $connection->query( $statement );
    }

    /**
     * Query a table to select where certain columns are certain values.
     *
     * @param array $what
     * @param string $which
     * @param array $where
     *
     * @return array
     */
    public static function select( $what, $which, $where = false ) {
        // Start by preparing the query string...
        $query = 'SELECT ';
        $query .= implode( $what, ', ' ) . ' ';
        $query .= 'FROM '. self::getTableName( $which ) . ' ';

        if ( $where ) {
            // Create the where array.
            $where = self::makeArrayColVal( $where );

            $query .= 'WHERE ' . implode( $where, ', ' );
        }

        // Execute the function.
        $res = self::query( $query );

        // Create the two dimensional array.
        $array = array();
        while( $row = $res->fetch_assoc() ) {
            $array[] = $row;
        }

        // Close the resonse.
        $res->close();

        return $array;
    }

    /**
     * Perform an insert into a table.
     *
     * @param string $table
     * @param array $data
     *
     * @return mysqli::result || false
     */
    public static function insert( $table, $data ) {
        $data = self::filterEmptyArray( $data );


        $keys   = self::addQuotes( array_keys( $data ), '`' );
        $values = self::addQuotes( array_values( $data ), '\'' );

        // Create the query string.
        $query = 'INSERT INTO ' . self::getTableName( $table ) . ' ';
        $query .= '(' . implode( $keys, ', ' ) . ')' . ' ';
        $query .= 'VALUES (' . implode( $values, ', ' ) . ')';

        echo $query
;
        // Get the connection
        $connection = self::getConnection();

        return $connection->query( $query );
    }

    /**
     * Perform an insert into a table.
     *
     * @param string $table
     * @param array $data
     *
     * @return mysqli::result || false
     */
    public static function update( $table, $data, $where ) {
        $data = self::filterEmptyArray( $data );
        // Create the query string.
        $query = 'UPDATE ' . self::getTableName( $table ) . ' ';
        $query .= 'SET ' . implode( self::makeArrayColVal( $data ), ', ' ) . ' ';
        $query .= 'WHERE ' . implode( self::makeArrayColVal( $where ), ', ' );

        // Get the connection
        $connection = self::getConnection();

        return $connection->query( $query );
    }

    /**
     * Saves a data type based off of its class name.
     *
     * If the object has an id then update else create a new instace of it.
     *
     * @param $object DataType
     *
     * @return DataType || false
     */
    public static function save( &$object ) {
       // Check if the object has an id, if not save the object as a new and get
       // its id. Ensure the id is valid.
       $id = isset( $object->id ) ? $object->id : 0;

       // Get the table name from the user.
       if ( ! defined( get_class( $object ) . '::TableName' ) ) { return false; }
       $tableName = constant( get_class( $object ) . '::TableName'  );

       if ( $id  ) {
           $res = Database::query( "SELECT id FROM $tableName WHERE id=$id" );
           if ( $res->num_rows == 1 ) {
               self::update( $tableName, $object->getData(), array( 'id' => $id ) );
               return $object;
           }
       }

       // Insert as a new object.
       self::insert( $tableName, $object->getData() );

       // Change the id.
       $connection = self::getConnection();
       $object->setDataVar( 'id', $connection->insert_id );

       // Return the object in case that is necessary.
       return $object;
    }
}
