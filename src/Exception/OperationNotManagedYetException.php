<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class OperationNotManagedYetException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class OperationNotManagedYetException extends AbstractException
{
    /** @var string */
    private $operation;

    public function __construct(string $operation)
    {
        $this->operation = $operation;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Operation '.$this->operation.' not managed';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
