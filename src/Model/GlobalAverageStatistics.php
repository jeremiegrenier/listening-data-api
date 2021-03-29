<?php

declare(strict_types=1);


namespace App\Model;

/**
 * Class GlobalAverageStatistics.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class GlobalAverageStatistics implements \JsonSerializable
{
    /** @var \DateTime Start date for average */
    private $startDate;

    /** @var \DateTime End date for average */
    private $endDate;

    /** @var string|null Average*/
    private $average;

    /** @var array<ArtistAverageStatistics> Average by artist*/
    private $artistAverageList;

    /**
     * GlobalAverageStatistics constructor.
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string|null $average
     * @param array $artistAverageList
     */
    public function __construct(\DateTime $startDate, \DateTime $endDate, ?string $average, array $artistAverageList)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->average = $average;
        $this->artistAverageList = $artistAverageList;

        /** @var ArtistAverageStatistics $artistAverage */
        foreach ($this->artistAverageList as $artistAverage) {
            $artistAverage->setGlobalStreamNumber((int) $this->average);
        }
    }


    /**
     * @return array<string>
     */
    public function jsonSerialize(): array
    {
        return [
            'startDate' => $this->startDate->format('Y-m-d'),
            'endDate' => $this->endDate->format('Y-m-d'),
            'average' => $this->average ?? 'no data',
            'artistAverage' => $this->artistAverageList
        ];
    }
}
