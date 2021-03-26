<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;

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
            if (
                false === ($host = getenv('MYSQL_HOST'))
                || false === ($username = getenv('MYSQL_USERNAME'))
                || false === ($passwd = getenv('MYSQL_PASSWD'))
                || false === ($dbname = getenv('MYSQL_DBNAME'))
            ) {
                throw new DatabaseException();
            }

            $this->instance = new \mysqli($host, $username, $passwd, $dbname);
        }
        return $this->instance;
    }
}
