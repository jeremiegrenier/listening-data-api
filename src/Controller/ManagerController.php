<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\FormattedResponse;
use App\Repository\ArtistsDataRepository;
use App\Repository\MysqlConnection;

/**
 * Class ManagerController.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class ManagerController
{
    public function getCompiledData(): FormattedResponse
    {
        $repository = new ArtistsDataRepository(new MysqlConnection());

        $t = $repository->getNumberListeningForArtistForYear(1, 2021);

        return new FormattedResponse(
            true,
            '',
            'todo'
        );
    }
}
