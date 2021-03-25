<?php

declare(strict_types=1);


namespace App\Model;

/**
 * Class FormattedResponse.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class FormattedResponse implements \JsonSerializable
{
    /** @var mixed Formatted data of response */
    private $data;

    /** @var int Status code of response */
    private $statusCode;

    /**
     * FormattedResponse constructor.
     *
     * @param array<Error>|null $errors
     * @param mixed             $data
     */
    public function __construct(bool $success, string $message, $data, int $status = 200, ?array $errors = [])
    {
        $this->data = [
            'timestamp' => (new \DateTime())->format(DATE_ATOM),
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ];
        $this->statusCode = $status;
    }

    /**
     * @return array<array<\JsonSerializable>>
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
