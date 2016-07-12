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
 * Place photo which describes a place photo.
 *
 * @author Rauflet Nicolas <nicolas.rauflet@outlook.fr>
 */
class PlaceOpeningHours
{
    /** @var int */
    protected $openNow;

    /** @var array */
    protected $periods;

    /** @var array */
    protected $weekdayText;

    /**
     * Create a geocoder geometry.
     *
     * @param \Ivory\GoogleMap\Base\Coordinate $openNow     The boolean to say if open or no.
     * @param string                           $periods The geometry All period of day.
     * @param \Ivory\GoogleMap\Base\Bound      $weekdayText     Text of open day.
     */
    public function __construct($openNow, $periods, $weekdayText)
    {
        $this->openNow = $openNow;
        $this->periods = $periods;
        $this->weekdayText = $weekdayText;
    }

}
