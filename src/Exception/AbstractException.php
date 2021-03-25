<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

/**
 * Class AbstractException
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
abstract class AbstractException extends Exception implements ExceptionInterface
{

    public function __construct()
    {
        parent::__construct(static::getExceptionMessage(), static::getStatusCode());
    }

    public function jsonSerialize()
    {
        return [
            'message' => static::getExceptionMessage(),
        ];
    }
}
