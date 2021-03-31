<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class OperationNotExistException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class OperationNotExistException extends AbstractException
{
    /** @var string */
    private $operation;

    public function __construct(string $operation)
    {
        $this->$operation = $operation;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Operation '.$this->operation.' not exist';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
