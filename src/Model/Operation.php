<?php

namespace App\Model;

class Operation
{
    /**
     * @var string
     */
    private $op;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string|null
     */
    private $value;

    /**
     * Operation constructor.
     * @param string $op
     * @param string $field
     * @param string|null $value
     */
    public function __construct(string $op, string $field, ?string $value)
    {
        $this->op = $op;
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getOp(): string
    {
        return $this->op;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
