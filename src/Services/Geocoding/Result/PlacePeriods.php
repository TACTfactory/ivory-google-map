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
class PlacePeriods
{
    /** @var PlaceOpenClose */
    protected $open;

    /** @var PlaceOpenClose */
    protected $close;

    /**
     * Create a place period.
     *
     * @param \Ivory\GoogleMap\Base\Coordinate $day     Get the $open value.
     * @param string                           $time    Get the $close value.
     */
    public function __construct($open, $close)
    {
        $this->open = $open;
        $this->close = $close;
    }
    
    public function getOpen() {
        return $this->open;
    }
    
    public function getClose() {
        return $this->close;
    }
}
