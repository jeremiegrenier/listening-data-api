<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\InvalidDateException;
use App\Manager\ArtistDataManager;
use App\Model\FormattedResponse;
use Psr\Log\LoggerInterface;

/**
 * Class ManagerController.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ManagerController
{
    /** @var ArtistDataManager */
    private $artistDataManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ManagerController constructor.
     *
     * @param ArtistDataManager $artistDataManager
     * @param LoggerInterface $logger
     */
    public function __construct(ArtistDataManager $artistDataManager, LoggerInterface $logger)
    {
        $this->artistDataManager = $artistDataManager;
        $this->logger = $logger;
    }


    public function getCompiledData(array $queryString): FormattedResponse
    {
        $this->logger->info(
            'Fetch global statistics for manager',
            []
        );

        if (!array_key_exists('date', $queryString) || !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $queryString['date'])) {
            $this->logger->info(
                'Invalid date given to get global statistics',
                []
            );

            throw new InvalidDateException();
        }

        $date = $queryString['date'];

        $this->logger->info(
            'Fetch global statistics for manager',
            [
                'date' => $date,
            ]
        );

        return new FormattedResponse(
            true,
            '',
            $this->artistDataManager->getGlobalStatisticsFromDate(new \DateTime($date))
        );
    }
}
