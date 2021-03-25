<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class DatabaseException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class DatabaseException extends AbstractException
{
    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Internal error';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 500;
    }
}
