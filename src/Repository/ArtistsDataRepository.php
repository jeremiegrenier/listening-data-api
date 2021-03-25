<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;

/**
 * Class ArtistsDataRepository.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ArtistsDataRepository
{
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

    public function getNumberListeningForArtistForYear(int $artistId, int $year): int
    {
        $startDate = (new \DateTime('first day of January '.$year))->format('Y-m-d');
        $endDate = (new \DateTime('last day of December '.$year))->format('Y-m-d');

        $sql = "SELECT SUM(nb_streams) as `nb_streams`
                FROM artists_data
                INNER JOIN artists 
                ON artists.id = artists_data.artist_id
                WHERE artists.id = ?
                AND artists_data.date >= ?
                AND artists_data.date < ?                
        ";

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if(false === $stmt)
        {
            throw new DatabaseException();
        }

        $stmt->bind_param('iss', $artistId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if(false === $result)
        {
            throw new DatabaseException();
        }

        return (int) $result->fetch_assoc()['nb_streams'];
    }

    public function getNumberListeningForArtistForYearByGender(int $artistId, int $year): array
    {
        $startDate = (new \DateTime('first day of January '.$year))->format('Y-m-d');
        $endDate = (new \DateTime('last day of December '.$year))->format('Y-m-d');

        $sql = "SELECT gender, SUM(nb_streams) as `nb_streams`
                FROM artists_data
                INNER JOIN artists 
                ON artists.id = artists_data.artist_id
                WHERE artists.id = ?
                AND artists_data.date >= ?
                AND artists_data.date < ?     
                GROUP BY gender          
        ";

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if(false === $stmt)
        {
            throw new DatabaseException();
        }

        $stmt->bind_param('iss', $artistId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if(false === $result)
        {
            throw new DatabaseException();
        }

        $results = [];
        while (null !== ($r = $result->fetch_assoc())) {
            $results[] = $r;
        }

        return $results;
    }
}
