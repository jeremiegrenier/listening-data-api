<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class MethodNotAllowedException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class MethodNotAllowedException extends AbstractException
{
    /** @var string */
    private $method;

    public function __construct(string $method)
    {
        parent::__construct();
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Method "'.$this->method.'"is not allowed';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 403;
    }
}
