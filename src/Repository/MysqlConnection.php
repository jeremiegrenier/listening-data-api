<?php

declare(strict_types=1);

namespace App\Repository;

/**
 * Class MysqlConnection.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class MysqlConnection
{
    /** @var \mysqli */
    private $instance;

    public function getInstance(): \mysqli
    {
        if (null === $this->instance) {
            $this->instance = new \mysqli(
                getenv('MYSQL_HOST'),
                getenv('MYSQL_USERNAME'),
                getenv('MYSQL_PASSWD'),
                getenv('MYSQL_DBNAME')
            );
        }
        return $this->instance;
    }
}
