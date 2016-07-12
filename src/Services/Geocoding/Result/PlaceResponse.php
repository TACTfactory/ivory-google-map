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

use Ivory\GoogleMap\Exception\GeocodingException;

/**
 * A place response wraps the place results & the response status.
 *
 * @author Rauflet Nicolas <nicolas.rauflet@outlook.fr>
 */
class PlaceResponse
{
    /** @var array */
    protected $results;

    /** @var string */
    protected $status;

    /**
     * Create a geocoder results.
     *
     * @param array  $results The geocoder results.
     * @param string $status  The geocoder status.
     */
    public function __construct(array $results, $status)
    {
        $this->setResults($results);
        $this->setStatus($status);
    }

    /**
     * Gets the geocoder results.
     *
     * @return array The geocoder results.
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Sets the geocoder results
     *
     * @param array $results The geocoder results.
     */
    public function setResults(array $results)
    {
        $this->results = array();

        foreach ($results as $result) {
            $this->addResult($result);
        }
    }

    /**
     * Adds a geocoder result.
     *
     * @param \Ivory\GoogleMap\Services\Result\GeocoderResult $result The geocoder result to add.
     */
    public function addResult(PlaceResult $result)
    {
        $this->results[] = $result;
    }

    /**
     * Gets the geocoder results status.
     *
     * @return string The geocoder results status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the geocoder results status.
     *
     * @param string $status The geocoder result status.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the status is not valid.
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
