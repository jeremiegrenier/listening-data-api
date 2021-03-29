<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class InvalidDateException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class InvalidDateException extends AbstractException
{
    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Date given is invalid';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
