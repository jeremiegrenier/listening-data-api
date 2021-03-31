<?php

declare(strict_types=1);

namespace App\Manager;

use App\Exception\ArtistNotFoundException;
use App\Exception\MissingOperationElementException;
use App\Exception\OperationNotExistException;
use App\Exception\OperationNotManagedYetException;
use App\Model\GenderStatistic;
use App\Model\ListeningStatistics;
use App\Model\Operation;
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

    /**
     * @param int $artistId
     * @param array<Operation> $operations
     *
     * @return bool
     *
     * @throws ArtistNotFoundException
     * @throws MissingOperationElementException
     */
    public function updateArtistsFromOperation(int $artistId, array $operations): bool
    {
        if (!$this->artistsRepository->isArtistExist($artistId)) {
            throw new ArtistNotFoundException($artistId);
        }

        /** @var Operation $operation */
        foreach ($operations as $operation) {
            $op = $operation->getOp();
            $value = $operation->getValue();

            switch ($op) {
                case self::PATCH_REPLACE:
                    $this->applyReplaceOperation($artistId, $operation->getField(), $value);
                    break;
                case self::PATCH_REMOVE:
                case self::PATCH_MOVE:
                case self::PATCH_COPY:
                case self::PATCH_ADD:
                    throw new OperationNotManagedYetException($op);
                default:
                    throw new OperationNotExistException($op);
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
