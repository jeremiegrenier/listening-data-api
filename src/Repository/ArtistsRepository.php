<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;
use App\Exception\UpdateNotAllowedException;
use Psr\Log\LoggerInterface;

/**
 * Class ArtistsRepository.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistsRepository
{
    const ALLOWED_FIELDS_UPDATE = ['twitter', 'status'];

    /** @var MysqlConnection */
    private $connection;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ArtistsRepository constructor.
     * @param MysqlConnection $connection
     * @param LoggerInterface $logger
     */
    public function __construct(MysqlConnection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function isArtistExist(int $artistId): bool
    {
        $sql = "SELECT id
                FROM artists
                WHERE artists.id = ?                
        ";

        $this->logger->info(
            'Execute query to check if artist exist',
            [
                'artistId' => $artistId,
            ]
        );

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);

        $stmt->bind_param('i', $artistId);
        if (!$stmt->execute()) {
            $this->logger->error(
                'Error when execute query to check if artist exist',
                [
                    'artistId' => $artistId,
                ]
            );

            return false;
        }
        $stmt->store_result();
        $stmt->fetch();

        return 0 !== $stmt->num_rows;
    }

    public function updateFieldOnArtist(int $artistId, string $field, $value): bool
    {
        $this->logger->info(
            'Update field for artist',
            [
                'artistId' => $artistId,
                'field' => $field,
                'value' => $value,
            ]
        );

        if (!in_array($field, self::ALLOWED_FIELDS_UPDATE)) {
            $this->logger->error(
                'Field to update is not allowed',
                [
                    'artistId' => $artistId,
                    'field' => $field,
                    'value' => $value,
                ]
            );

            throw new UpdateNotAllowedException();
        }

        $sql = "UPDATE `artists`
                SET `".$field."` = ? 
                WHERE artists.id = ?          
        ";

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if (false === $stmt) {
            $this->logger->error(
                'Error when prepare query to update field on artist',
                [
                    'artistId' => $artistId,
                    'field' => $field,
                    'value' => $value,
                ]
            );

            throw new DatabaseException();
        }

        $stmt->bind_param('si', $value, $artistId);
        $stmt->execute();
        return true;
    }
}
