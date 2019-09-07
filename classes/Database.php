<?php

class Database extends PDO
{

    /**
     * Database constructor.
     */
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

        parent::__construct($dsn, $user, $pass, $options);
    }

    /**
     * Destroy this instance to close the database
     */
    function __destruct()
    {
        unset($this);
    }

    /**
     * Quotes a param with MySQL approved quotes
     * @param string $string to quote
     * @param int $parameter_type
     * @return string
     */
    function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        return parent::quote($string, $parameter_type);
    }

    /**
     * Quotes columns with ` for MySQL approved column quotes
     * @param string $column to quote
     * @return string
     */
    function quoteColumn($column)
    {
        return '`' . $column . '`';
    }

    /**
     * Destroys the current instance of this database
     */
    function close()
    {
        $this->__destruct();
    }

    /**
     * Queries the database to gather rows
     * @param string $query to run
     * @param string[] cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return string[]
     */
    public function query($query, $cols = array(), $args = array())
    {
        $results = array();

        //replace all instances of ! with a quoted column
        foreach($cols as $col)
            $query = preg_replace('/!/', $this->quoteColumn($col), $query, 1);

        //prepare the MySQL statement
        if ($pdoStatement = $this->prepare($query))
        {
            //execute and get the results
            if($pdoStatement->execute($args))
                $results = $pdoStatement->fetchAll();
        }

        //close the PDO statement
        $pdoStatement = null;

        return $results;
    }

    /**
     * Inserts or updates a record in the database
     * @param string $query to run
     * @param string[] $cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return int
     */
    public function insertOrUpdate($query, $cols = array(), $args = array())
    {
        $insertId = -1;

        //replace all instances of ! with a quoted column
        foreach($cols as $col)
            $query = preg_replace('/!/', $this->quoteColumn($col), $query, 1);

        //prepare the MySQL statement
        if ($pdoStatement = $this->prepare($query))
        {
            //execute and get the results
            if($pdoStatement->execute($args))
                $insertId = $this->lastInsertId();
        }

        //close the PDO statement
        $pdoStatement = null;

        return $insertId;

    }

    /**
     * Deletes a record in the database
     * @param string $query to run
     * @param string[] $cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return int
     */
    public function delete($query, $cols = array(), $args = array())
    {
        $success = false;

        //replace all instances of ! with a quoted column
        foreach($cols as $col)
            $query = preg_replace('/!/', $this->quoteColumn($col), $query, 1);

        //prepare the MySQL statement
        if ($pdoStatement = $this->prepare($query))
            //execute and get the results
            $success = $pdoStatement->execute($args);


        //close the PDO statement
        $pdoStatement = null;

        return $success;

    }

}

?>