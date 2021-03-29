<?php

declare(strict_types=1);


namespace App\Model;

/**
 * Class ArtistAverageStatistics.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistAverageStatistics implements \JsonSerializable
{
    /** @var int Artist id */
    private $artistId;

    /** @var string Average*/
    private $average;

    /** @var int Average*/
    private $globalStream;

    /**
     * ArtistAverageStatistics constructor.
     * @param int $artistId
     * @param string $average
     */
    public function __construct(int $artistId, string $average)
    {
        $this->artistId = $artistId;
        $this->average = $average;
        $this->globalStream = null;
    }

    public function setGlobalStreamNumber(int $globalStream): void
    {
        $this->globalStream = $globalStream;
    }

    /**
     * @return array<string>
     */
    public function jsonSerialize(): array
    {
        $data = [
            'artistId' => $this->artistId,
            'average' => $this->average,
        ];

        if (null !== $this->globalStream) {
            $data['percentage'] = $this->average / $this->globalStream * 100;
        }

        return $data;
    }
}
