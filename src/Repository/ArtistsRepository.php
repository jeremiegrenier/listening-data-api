<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;
use App\Exception\UpdateNotAllowedException;

/**
 * Class ArtistsRepository.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistsRepository
{
    CONST ALLOWED_FIELDS_UPDATE = ['twitter', 'status'];

    /** @var MysqlConnection */
    private $connection;

    /**
     * ArtistsDataRepository constructor.
     * @param MysqlConnection $connection
     */
    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }

    public function isArtistExist(int $artistId): bool
    {
        $sql = "SELECT id
                FROM artists
                WHERE artists.id = ?                
        ";

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);

        $stmt->bind_param('i', $artistId);
        $stmt->execute();
        $result = $stmt->get_result();

        return 0 !== $result->num_rows;
    }

    public function updateFieldOnArtist(int $artistId, string $field, $value): bool
    {
        if(!in_array($field, self::ALLOWED_FIELDS_UPDATE))
        {
            throw new UpdateNotAllowedException();
        }

        $sql = "UPDATE `artists`
                SET `".$field."` = ? 
                WHERE artists.id = ?          
        ";

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if(false === $stmt)
        {
            throw new DatabaseException($i->error);
        }

        $stmt->bind_param('si', $value, $artistId);
        $stmt->execute();
        return true;
    }
}
