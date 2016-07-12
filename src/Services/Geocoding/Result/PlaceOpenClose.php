<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Rauflet Nicolas <rauflet.nicolas@outlook.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Services\Geocoding\Result;

use Ivory\GoogleMap\Base\Bound;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Exception\GeocodingException;

/**
 * PlacePeriod which describe day open and close of day period.
 *
 * @author Rauflet Nicolas <nicolas.rauflet@outlook.fr>
 */
class PlaceOpenClose
{
    /** @var int */
    protected $day;

    /** @var string */
    protected $time;

    /**
     * Create a place period.
     *
     * @param \Ivory\GoogleMap\Base\Coordinate $day     Get the $day value.
     * @param string                           $time    Get the $time value.
     */
    public function __construct($day, $time)
    {
        $this->setDay($day);
        $this->setTime($time);
    }

    /**
     * Get day value.
     *
     * @return int height.
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set day value.
     *
     * @param $day value.
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * Get day time.
     *
     * @return integer day time.
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time of day.
     *
     * @param integer time.
     *.
     */
    public function setTime($time)
    {
        $this->time = $time;
    }
}
