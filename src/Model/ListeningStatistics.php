<?php

declare(strict_types=1);


namespace App\Model;

/**
 * Class ListeningStatistics.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ListeningStatistics implements \JsonSerializable
{
    /** @var string Year */
    private $year;

    /** @var int Number of listening for artists */
    private $listeningNumber;

    /** @var array<GenderStatistic> List of statistics by gender */
    private $genderStatistics;

    /**
     * ListeningStatistics constructor.
     * @param string $year
     * @param int $listeningNumber
     * @param array $genderStatistics
     */
    public function __construct(string $year, int $listeningNumber, array $genderStatistics)
    {
        $this->year = $year;
        $this->listeningNumber = $listeningNumber;
        $this->genderStatistics = $genderStatistics;
    }

    /**
     * @return array<string>
     */
    public function jsonSerialize(): array
    {
        return [
            'year' => $this->year,
            'listeningNumber' => $this->listeningNumber,
            'genderStat' => $this->genderStatistics,
        ];
    }
}
