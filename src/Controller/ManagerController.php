<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\FormattedResponse;

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
        return new FormattedResponse(
            true,
            '',
            'todo'
        );
    }
}
