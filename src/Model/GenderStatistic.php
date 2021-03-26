<?php

declare(strict_types=1);


namespace App\Model;

/**
 * Class GenderStatistic.
 *
 * @author jgrenier
 *
 * @version 1.0.0
 */
class GenderStatistic implements \JsonSerializable
{
    /** @var string Gender for statistic */
    private $gender;

    /** @var int Number of stream for this gender */
    private $listeningNumber;

    /** @var float Percentage of stream by gender */
    private $percentage;

    /**
     * GenderStatistic constructor.
     *
     * @param string $gender
     * @param int $listeningNumber
     * @param float $percentage
     */
    public function __construct(string $gender, int $listeningNumber, float $percentage)
    {
        $this->gender = $gender;
        $this->listeningNumber = $listeningNumber;
        $this->percentage = $percentage;
    }


    /**
     * @return array<string>
     */
    public function jsonSerialize(): array
    {
        return [
            'gender' => $this->gender,
            'nb_streams' => $this->listeningNumber,
            'percentage' => $this->percentage,
        ];
    }
}
