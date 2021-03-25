<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * class ArtistNotFoundException.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistNotFoundException extends AbstractException
{
    /** @var int */
    private $artistId;

    public function __construct(int $artistId)
    {
        parent::__construct();
        $this->artistId = $artistId;
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionMessage(): string
    {
        return 'Artist "'.$this->artistId.'" not exist';
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return 404;
    }
}
