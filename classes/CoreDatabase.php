<?php

class CoreDatabase extends Database
{
    /**
     * Database constructor.
     */
    function __construct()
    {
        require_once(ROOT_DIR . '/classes/tables/core/CoreTable.php');

        parent::__construct(CoreTable::$DB_NAME);
    }
}

?>