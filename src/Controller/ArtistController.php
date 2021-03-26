<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ArtistNotFoundException;
use App\Exception\InvalidOperationFormatException;
use App\Manager\ArtistsManager;
use App\Model\FormattedResponse;
use Psr\Log\LoggerInterface;

/**
 * Class ArtistController.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistController
{
    /** @var ArtistsManager */
    private $artistsManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ArtistController constructor.
     *
     * @param ArtistsManager $artistsManager
     * @param LoggerInterface $logger
     */
    public function __construct(ArtistsManager $artistsManager, LoggerInterface $logger)
    {
        $this->artistsManager = $artistsManager;
        $this->logger = $logger;
    }

    /**
     * @param string $artistId
     * @param array<string> $queryString
     *
     * @return FormattedResponse
     *
     * @throws ArtistNotFoundException
     */
    public function getListeningStatistics(string $artistId, array $queryString): FormattedResponse
    {
        $year = array_key_exists('year', $queryString) && preg_match('/^[1-2]\d\d\d$/', $queryString['year'])
            ? $queryString['year']
            : (new \DateTime())->format('Y');

        $this->logger->info(
            'Fetch statistics for artist',
            [
                'artistId' => $artistId,
                'year' => $year
            ]
        );

        return new FormattedResponse(
            true,
            '',
            $this->artistsManager->getListeningStatistics((int) $artistId, $year)
        );
    }

    /**
     * @param string $artistId
     *
     * @return FormattedResponse
     *
     * @throws ArtistNotFoundException
     */
    public function patchArtist(string $artistId, ?array $body): FormattedResponse
    {
        $this->logger->info(
            'Ask to patch artist',
            [
                'artistId' => $artistId,
                'operations' => $body,
            ]
        );

        if (!$this->isValidOperationArray($body)) {
            $this->logger->warning(
                'Operations to update artist invalid',
                [
                    'artistId' => $artistId,
                    'operations' => $body,
                ]
            );

            throw new InvalidOperationFormatException();
        }

        $this->artistsManager->updateArtistsFromOperation((int) $artistId, $body);

        $this->logger->info(
            'Artist updated',
            [
                'artistId' => $artistId,
                'operations' => $body,
            ]
        );

        return new FormattedResponse(
            true,
            '',
            []
        );
    }

    private function isValidOperationArray($operations): bool
    {
        if (!is_array($operations)) {
            return false;
        }

        foreach ($operations as $operation) {
            if (
                !is_array($operation)
                || !array_key_exists('op', $operation)
                || !array_key_exists('path', $operation)
            ) {
                return false;
            }
        }

        return true;
    }
}
