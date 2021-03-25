<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class UpdateNotAllowedException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class UpdateNotAllowedException extends AbstractException
{
    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Fields can\'t be updated';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 401;
    }
}
