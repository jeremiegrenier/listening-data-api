<?php

declare(strict_types=1);

namespace App\Manager;

use App\Exception\ArtistNotFoundException;
use App\Exception\MissingOperationElementException;
use App\Model\GenderStatistic;
use App\Model\ListeningStatistics;
use App\Repository\ArtistsDataRepository;
use App\Repository\ArtistsRepository;

/**
 * Class ArtistsManager.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistsManager
{
    public const PATCH_REMOVE = 'remove';
    public const PATCH_ADD = 'add';
    public const PATCH_REPLACE = 'replace';
    public const PATCH_MOVE = 'move';
    public const PATCH_COPY = 'copy';

    /** @var ArtistsRepository */
    private $artistsRepository;

    /** @var ArtistsDataRepository */
    private $artistsDataRepository;

    /**
     * ArtistsManager constructor.
     * @param ArtistsRepository $artistsRepository
     * @param ArtistsDataRepository $artistsDataRepository
     */
    public function __construct(ArtistsRepository $artistsRepository, ArtistsDataRepository $artistsDataRepository)
    {
        $this->artistsRepository = $artistsRepository;
        $this->artistsDataRepository = $artistsDataRepository;
    }

    public function getListeningStatistics(int $artistId, string $year): ListeningStatistics
    {
        if (!$this->artistsRepository->isArtistExist($artistId)) {
            throw new ArtistNotFoundException($artistId);
        }

        $listeningNumber = $this->artistsDataRepository->getNumberListeningForArtistForYear((int) $artistId, (int) $year);
        $listeningByGender = $this->artistsDataRepository->getNumberListeningForArtistForYearByGender((int) $artistId, (int) $year);

        $genderStatistics = [];
        foreach ($listeningByGender as $genderStat) {
            $genderStatistics[] = new GenderStatistic(
                $genderStat['gender'],
                (int) $genderStat['nb_streams'],
                $genderStat['nb_streams'] / $listeningNumber * 100
            );
        }

        return new ListeningStatistics($year, $listeningNumber, $genderStatistics);
    }

    public function updateArtistsFromOperation(int $artistId, array $operations): bool
    {
        if (!$this->artistsRepository->isArtistExist($artistId)) {
            throw new ArtistNotFoundException($artistId);
        }

        foreach ($operations as $operation) {
            if (!array_key_exists('op', $operation)) {
                throw new MissingOperationElementException('op');
            }
            $op = $operation['op'];

            if (!array_key_exists('path', $operation)) {
                throw new MissingOperationElementException('path');
            }
            $path = $operation['path'];

            $value = isset($operation['value']) ? $operation['value'] : null;

//            $from = isset($operation['from']) ? $operation['from'] : null;

            switch ($op) {
                case self::PATCH_REPLACE:
                    $this->applyReplaceOperation($artistId, $path, $value);
                    break;
                case self::PATCH_REMOVE:
                case self::PATCH_MOVE:
                case self::PATCH_COPY:
                case self::PATCH_ADD:
                    throw new \InvalidArgumentException(sprintf('Operation %s not managed', $operation));
                default:
                    throw new \InvalidArgumentException(sprintf('Operation %s not exist', $operation));
            }
        }

        return true;
    }

    private function applyReplaceOperation(int $artistId, string $path, $value)
    {
        if (!isset($value)) {
            throw new MissingOperationElementException('value');
        }

        $this->artistsRepository->updateFieldOnArtist($artistId, $path, $value);
    }
}
