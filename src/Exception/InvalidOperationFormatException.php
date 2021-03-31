<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class InvalidOperationFormatException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class InvalidOperationFormatException extends AbstractException
{
    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Operations are invalid';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
