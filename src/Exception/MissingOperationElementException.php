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
class MissingOperationElementException extends AbstractException
{
    /** @var string */
    private $element;

    public function __construct(string $element)
    {
        parent::__construct();
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Missing parameter "'.$this->element.'" in operation';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
