<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class RouteNotFoundException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class RouteNotFoundException extends AbstractException
{
    /** @var string */
    private $route;

    public function __construct(string $route)
    {
        parent::__construct();
        $this->route = $route;
    }
    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Route "'.$this->route.'" not exist';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 404;
    }
}
