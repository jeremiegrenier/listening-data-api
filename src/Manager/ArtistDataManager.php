<?php

declare(strict_types=1);

namespace App\Manager;

use App\Exception\ArtistNotFoundException;
use App\Exception\MissingOperationElementException;
use App\Model\GenderStatistic;
use App\Model\GlobalAverageStatistics;
use App\Model\ListeningStatistics;
use App\Repository\ArtistsDataRepository;
use App\Repository\ArtistsRepository;

/**
 * Class ArtistDataManager.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistDataManager
{
    /** @var ArtistsDataRepository */
    private $artistsDataRepository;

    /**
     * ArtistDataManager constructor.
     *
     * @param ArtistsDataRepository $artistsDataRepository
     */
    public function __construct(ArtistsDataRepository $artistsDataRepository)
    {
        $this->artistsDataRepository = $artistsDataRepository;
    }

    public function getGlobalStatisticsFromDate(\DateTime $endDate): GlobalAverageStatistics
    {
        $startDate = clone $endDate;
        $startDate->modify('-3 days');

        return new GlobalAverageStatistics(
            $startDate,
            $endDate,
            $this->artistsDataRepository->getGlobalAverageFromDate($startDate, $endDate),
            $this->artistsDataRepository->getAverageByArtistFromDate($startDate, $endDate)
        );
    }
}
