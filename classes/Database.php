<?php

class Database
{
    var $classQuery;
    var $link;

    var $errno = '';
    var $error = '';

    // Connects to the database
    function __construct()
    {

        // Get the main settings from the array we just loaded
        $host = MYSQL_HOST;
        $name = MYSQL_DB;
        $user = MYSQL_USER;
        $pass = MYSQL_PASSWORD;

        $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

        $options = [
            PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];

        // Connect to the database
        $this->link = new PDO($dsn, $user, $pass, $options);
    }

    // Executes a database query
    function query( $query )
    {
        $this->classQuery = $query;
        return $this->link->query( $query );
    }

    function escapeString( $query )
    {
        return $this->link->escape_string( $query );
    }

    // Get the data return int result
    function numRows( $result )
    {
        return $result->num_rows;
    }

    function lastInsertedID()
    {
        return $this->link->lastInsertId();
    }

    // Get query using assoc method
    function fetchAssoc( $result )
    {
        return $result->fetch_assoc();
    }

    // Gets array of query results
    function fetchArray( $result , $resultType = MYSQLI_ASSOC )
    {
        return $result->fetch_array( $resultType );
    }

    // Fetches all result rows as an associative array, a numeric array, or both
    function fetchAll( $result , $resultType = MYSQLI_ASSOC )
    {
        return $result->fetch_all( $resultType );
    }

    // Get a result row as an enumerated array
    function fetchRow( $result )
    {
        return $result->fetch_row();
    }

    // Free all MySQL result memory
    function freeResult( $result )
    {
        $this->link->free_result( $result );
    }

    //Closes the database connection
    function close()
    {
        $this->link = null;
    }

    //quotes the string
    function quote($string)
    {
        return '"' . $string . '"';
    }

    //quotes the string
    function quoteColumn($string)
    {
        return '`' . $string . '`';
    }

    function sql_error()
    {
        if( empty( $error ) )
        {
            $errno = $this->link->errno;
            $error = $this->link->error;
        }
        return $errno . ' : ' . $error;
    }
}

?>