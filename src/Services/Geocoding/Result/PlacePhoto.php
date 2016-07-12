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
class PlacePhoto
{
    /** @var int */
    protected $height;

    /** @var int */
    protected $width;

    /** @var string */
    protected $htmlAttribution;

    /** @var string */
    protected $photoReference;

    /**
     * Create a geocoder geometry.
     *
     * @param \Ivory\GoogleMap\Base\Coordinate $height     The height of photo.
     * @param string                           $width The geometry The width of photo.
     * @param \Ivory\GoogleMap\Base\Bound      $htmlAttribution     The html attribution of photo.
     * @param \Ivory\GoogleMap\Base\Bound      $photoReference        The unique reference of photo.
     */
    public function __construct($height, $width, $htmlAttribution, $photoReference)
    {
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setHtmlAttribution($htmlAttribution);
        $this->setPhotoReference($photoReference);
    }

    /**
     * Gets the height of photo.
     *
     * @return int height.
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Sets the height of photo.
     *
     * @param \Ivory\GoogleMap\Base\Coordinate $location The height.
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Gets the width of photo.
     *
     * @return integer width of photo.
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Sets the width of photo.
     *
     * @param integer width.
     *.
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Gets the html attribution of image
     *
     * @return \Ivory\GoogleMap\Base\Bound The html attribution.
     */
    public function getHtmlAttribution()
    {
        return $this->htmlAttribution;
    }

    /**
     * Sets the html attribution.
     *
     * @param \Ivory\GoogleMap\Base\Bound $viewport The html attribution.
     */
    public function setHtmlAttribution($htmlAttribution)
    {
        $this->htmlAttribution = $htmlAttribution;
    }

    /**
     * Gets the photo reference.
     *
     * @return \Ivory\GoogleMap\Base\Bound The photo reference.
     */
    public function getPhotoReference()
    {
        return $this->bound;
    }

    /**
     * Sets the photo reference.
     *
     * @param string photo reference.
     */
    public function setPhotoReference($photoReference = null)
    {
        $this->photoReference = $photoReference;
    }
}
