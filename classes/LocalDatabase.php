<?php

class LocalDatabase extends Database
{
    /**
     * Database constructor.
     */
    function __construct()
    {
        require_once(ROOT_DIR . '/classes/tables/local/LocalTable.php');

        parent::__construct(LocalTable::$DB_NAME);
    }
}

?>