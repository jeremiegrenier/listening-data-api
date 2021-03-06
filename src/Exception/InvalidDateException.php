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
    /** @var string */
    private $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Date given is invalid : '.$this->reason;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
