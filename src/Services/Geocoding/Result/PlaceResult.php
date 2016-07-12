<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Nicolas Rauflet <rauflet.nicolas@outlook.fr>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Services\Geocoding\Result;

use Ivory\GoogleMap\Exception\GeocodingException;

/**
 * Place result which describes a google map place result.
 *
 * @author Nicolas Rauflet <rauflet.nicolas@outlook.fr>
 */
class PlaceResult
{
    /** @var array */
    protected $address_components;

    /** @var string */
    protected $adrAddress;

    /** @var string */
    protected $formattedAddress;

    /** @var string */
    protected $formatedPhoneNumber;

    /** @var \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry */
    protected $geometry;

    /** @var string */
    protected $icon;

    /** @var string */
    protected $id;

    /** @var string */
    protected $internationalPhoneNumber;

    /** @var string */
    protected $name;

    /** @var array */
    protected $photos;

    /** @var string */
    protected $placeId;

    /** @var string */
    protected $rating;

    /** @var string */
    protected $reference;

    /** @var array */
    protected $reviews;

    /** @var array */
    protected $types;

    /** @var string */
    protected $url;

    /** @var string */
    protected $utcOffset;

    /** @var string */
    protected $vinicity;

    /** @var string */
    protected $website;

    /** @var PlaceOpeningHours */
    protected $openingHours;

    /**
     * Create a gecoder result.
     *
     * @param array                                                       $addressComponents The address components.
     * @param string                                                      $formattedAddress  The formatted address.
     * @param \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry $geometry          The geometry.
     * @param array                                                       $types             The types.
     * @param boolean                                                     $partialMatch      The partial match flag.
     */
    public function __construct(array $addressComponents, $formattedAddress, GeocoderGeometry $geometry, $adrAddress,
            $formatedPhoneNumber, $icon, $id, $internationalPhoneNumber, $name, array $photos,
            $placeId, $rating, $reference, $reviews, $url, array $types, $utcOffset, $vinicity, $website,
            $openingHours) {
        $this->setAddressComponents($addressComponents);
        $this->setFormattedAddress($formattedAddress);
        $this->setGeometry($geometry);
        $this->adrAddress = $adrAddress;
        $this->formatedPhoneNumber = $formatedPhoneNumber;
        $this->icon = $icon;
        $this->id = $id;
        $this->internationalPhoneNumber = $internationalPhoneNumber;
        $this->name = $name;
        $this->photos = $photos;
        $this->placeId = $placeId;
        $this->rating = $rating;
        $this->reference = $reference;
        $this->reviews = $reviews;
        $this->url = $url;
        $this->utcOffset = $utcOffset;
        $this->vinicity = $vinicity;
        $this->website = $website;
        $this->setTypes($types);
        $this->setPlaceId($placeId);
        $this->openingHours = $openingHours;
    }

    /**
     * Gets the address components.
     *
     * @param string|null The type of the address components.
     *
     * @return array The address components.
     */
    public function getAddressComponents($type = null)
    {
        if ($type === null) {
            return $this->addressComponents;
        }

        $addressComponents = array();

        foreach ($this->addressComponents as $addressComponent) {
            if (in_array($type, $addressComponent->getTypes())) {
                $addressComponents[] = $addressComponent;
            }
        }

        return $addressComponents;
    }

    /**
     * Sets address components.
     *
     * @param array $addressComponents The address components.
     */
    public function setAddressComponents(array $addressComponents)
    {
        $this->addressComponents = array();

        foreach ($addressComponents as $addressComponent) {
            $this->addAddressComponent($addressComponent);
        }
    }

    /**
     * Adds an address component to the geocoder result.
     *
     * @param \Ivory\GoogleMapBundle\Model\Services\Result\GeocoderAddressComponent $addressComponent The address
     *                                                                                                component to add.
     */
    public function addAddressComponent(GeocoderAddressComponent $addressComponent)
    {
        $this->addressComponents[] = $addressComponent;
    }

    /**
     * Gets the formatted address.
     *
     * @return string The formatted address.
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Sets the formatted address.
     *
     * @param string $formattedAddress The formatted address.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the formatted address is not valid.
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;
    }

    /**
     * Gets the geocoder result geometry.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry The geocoder result geometry.
     */
    public function getGeometry()
    {
        return $this->geometry;
    }

    /**
     * Sets the geocoder result geometry.
     *
     * @param \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry $geometry The geocoder result geometry.
     */
    public function setGeometry(GeocoderGeometry $geometry)
    {
        $this->geometry = $geometry;
    }

    /**
     * Sets the geocoder result placeId.
     *
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;
    }

    /**
     * Get placeId.
     *
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Gets the geocoder result types.
     *
     * @return array The geocoder result types.
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Sets the geocoder result types.
     *
     * @param array $types The geocoder result types.
     */
    public function setTypes(array $types)
    {
        $this->types = array();

        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * Adds a type to the geocoder result.
     *
     * @param string $type The type to add.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the type is not valid.
     */
    public function addType($type)
    {
        if (!is_string($type)) {
            throw GeocodingException::invalidGeocoderResultType();
        }

        $this->types[] = $type;
    }
}
