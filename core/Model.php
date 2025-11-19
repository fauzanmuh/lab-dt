<?php

namespace Core;

use Core\Database\Database;

/**
 * Base Model Class
 * Simple base class that provides database access
 */
class Model
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Get database instance
     */
    protected function getDb(): Database
    {
        return $this->db;
    }
}
