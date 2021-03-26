<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ArtistNotFoundException;
use App\Exception\InvalidOperationFormatException;
use App\Manager\ArtistsManager;
use App\Model\FormattedResponse;

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

    /**
     * ArtistController constructor.
     *
     * @param ArtistsManager $artistsManager
     */
    public function __construct(ArtistsManager $artistsManager)
    {
        $this->artistsManager = $artistsManager;
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
        if (!$this->isValidOperationArray($body)) {
            throw new InvalidOperationFormatException();
        }

        $this->artistsManager->updateArtistsFromOperation((int) $artistId, $body);

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
            if (!is_array($operation)) {
                return false;
            }

            if (!array_key_exists('op', $operation)) {
                return  false;
            }

            if (!array_key_exists('path', $operation)) {
                return  false;
            }
        }

        return true;
    }
}
