<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Interface ExceptionInterface
 */
interface ExceptionInterface extends \JsonSerializable
{
    public function getExceptionMessage(): string;

    public function getStatusCode(): int;
}
