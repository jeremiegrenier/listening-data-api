<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;
use App\Model\ArtistAverageStatistics;
use Psr\Log\LoggerInterface;

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

    /** @var LoggerInterface */
    private $logger;

    /**
     * ArtistsDataRepository constructor.
     * @param MysqlConnection $connection
     * @param LoggerInterface $logger
     */
    public function __construct(MysqlConnection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
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

        $this->logger->info(
            'Execute query to get number of streams for artist',
            [
                'artistId' => $artistId,
                'year' => $year,
            ]
        );

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if (false === $stmt) {
            $this->logger->error(
                'Error when prepare query to get number of streams for artist',
                [
                    'artistId' => $artistId,
                    'year' => $year,
                ]
            );

            throw new DatabaseException();
        }

        $stmt->bind_param('iss', $artistId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if (false === $result) {
            $this->logger->error(
                'Error when execute query to get number of streams for artist',
                [
                    'artistId' => $artistId,
                    'year' => $year,
                ]
            );

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

        $this->logger->info(
            'Execute query to get number of streams for artist by gender',
            [
                'artistId' => $artistId,
                'year' => $year,
            ]
        );

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if (false === $stmt) {
            $this->logger->error(
                'Error when prepare query to get number of streams for artist by gender',
                [
                    'artistId' => $artistId,
                    'year' => $year,
                ]
            );

            throw new DatabaseException();
        }

        $stmt->bind_param('iss', $artistId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if (false === $result) {
            $this->logger->error(
                'Error when execute query to get number of streams for artist by gender',
                [
                    'artistId' => $artistId,
                    'year' => $year,
                ]
            );

            throw new DatabaseException();
        }

        $results = [];
        while (null !== ($r = $result->fetch_assoc())) {
            $results[] = $r;
        }

        return $results;
    }

    public function getGlobalAverageFromDate(\DateTime $startDate, \DateTime $endDate): ?string
    {
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $sql = "SELECT AVG(global_nb_streams) as average_global_nb_streams
                FROM (
                    SELECT date, SUM(nb_streams) as global_nb_streams
                    FROM artists_data
                    WHERE date BETWEEN ? AND ?
                    GROUP BY date
                ) t        
        ";

        $this->logger->info(
            'Execute query to get average number of streams',
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]
        );

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if (false === $stmt) {
            $this->logger->error(
                'Error when prepare query to get average number of streams',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'e' => $i->error
                ]
            );

            throw new DatabaseException();
        }

        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if (false === $result) {
            $this->logger->error(
                'Error when execute query to get average number of streams',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            );

            throw new DatabaseException();
        }

        $result = $result->fetch_array();

        return $result['average_global_nb_streams'];
    }

    public function getAverageByArtistFromDate(\DateTime $startDate, \DateTime $endDate): array
    {
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $sql = "SELECT artist_id, AVG(nb_streams_by_artist) as avg_stream_by_artist
                FROM(                
                    SELECT artist_id, date, SUM(nb_streams) as nb_streams_by_artist
                    FROM artists_data
                    WHERE date BETWEEN ? AND ?
                    GROUP BY artist_id, date
                ) t 
                GROUP BY artist_id
        ";

        $this->logger->info(
            'Execute query to get average number of streams by artist',
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]
        );

        $i = $this->connection->getInstance();
        $stmt = $i->prepare($sql);
        if (false === $stmt) {
            $this->logger->error(
                'Error when prepare query to get average number of streams by artist',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'error' => $i->error
                ]
            );

            throw new DatabaseException();
        }

        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if (false === $result) {
            $this->logger->error(
                'Error when execute query to get average number of streamsby artist',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'error' => $stmt->error,
                ]
            );

            throw new DatabaseException();
        }

        $results = [];
        while (null !== ($r = $result->fetch_assoc())) {
            $results[] = new ArtistAverageStatistics($r['artist_id'], $r['avg_stream_by_artist']);
        }

        return $results;
    }
}
