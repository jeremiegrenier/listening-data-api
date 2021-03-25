<?php

namespace App\Model;

class Error implements \JsonSerializable
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var string|null
     */
    private $context;

    public function __construct(string $message, string $detail, ?string $context = null)
    {
        $this->message = $message;
        $this->detail = $detail;
        $this->context = $context;
    }

    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'internalDetail' => $this->detail,
            'context' => $this->context,
        ];
    }
}
