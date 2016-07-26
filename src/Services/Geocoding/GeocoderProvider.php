<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Services\Geocoding;

use Geocoder\HttpAdapter\HttpAdapterInterface;
use Geocoder\Provider\AbstractProvider;
use Geocoder\Provider\ProviderInterface;
use Ivory\GoogleMap\Base\Bound;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Exception\GeocodingException;
use Ivory\GoogleMap\Services\BusinessAccount;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderAddressComponent;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse;
use Ivory\GoogleMap\Services\Geocoding\Result\PlaceResponse;
use Ivory\GoogleMap\Services\Geocoding\Result\PlaceResult;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResult;
use Ivory\GoogleMap\Services\Utils\XmlParser;
use Ivory\GoogleMap\Services\Geocoding\Result\PlacePhoto;
use Ivory\GoogleMap\Services\Geocoding\Result\PlaceOpeningHours;
use Ivory\GoogleMap\Services\Geocoding\Result\PlacePeriods;
use Ivory\GoogleMap\Services\Geocoding\Result\PlaceOpenClose;

/**
 * Geocoder provider.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class GeocoderProvider extends AbstractProvider implements ProviderInterface
{
    /** @var string */
    protected $url;

    /** @var boolean */
    protected $https;

    /** @var string */
    protected $format;

    /** @var \Ivory\GoogleMap\Services\Utils\XmlParser */
    protected $xmlParser;

    /** @var \Ivory\GoogleMap\Services\BusinessAccount */
    protected $businessAccount;

    /** @var string */
    protected $placeUrl;

    /** @var string */
    protected $placeUrlDetails;

    /** @var string */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function __construct(HttpAdapterInterface $adapter, $locale = null)
    {
        parent::__construct($adapter, $locale);

        $this->setUrl('http://maps.googleapis.com/maps/api/geocode');
        $this->setPlaceUrl('https://maps.googleapis.com/maps/api/place/textsearch');
        $this->setPlaceUrlDetails('https://maps.googleapis.com/maps/api/place/details');
        $this->setType('default');
        $this->setHttps(false);
        $this->setFormat('json');
        $this->setXmlParser(new XmlParser());
    }

    /**
     * Gets the service url according to the https flag.
     *
     * @return string The service url.
     */
    public function getUrl()
    {
        if ($this->isHttps()) {
            return str_replace('http://', 'https://', $this->url);
        }

        return $this->url;
    }

    /**
     * Sets the service url.
     *
     * @param string $url The service url.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the url is not valid.
     */
    public function setUrl($url)
    {
        if (!is_string($url)) {
            throw GeocodingException::invalidGeocoderProviderUrl();
        }

        $this->url = $url;
    }

    /**
     * Gets the service place url according to the https flag.
     *
     */
    public function getPlaceUrl()
    {
        if ($this->isHttps()) {
            return str_replace('http://', 'https://', $this->placeUrl);
        }

        return $this->placeUrl;
    }

    /**
     * Sets the service url.
     *
     * @param string $url The service url.
     *
     */
    public function setPlaceUrl($placeUrl)
    {
        if (!is_string($placeUrl)) {
            throw GeocodingException::invalidGeocoderProviderUrl();
        }

        $this->placeUrl = $placeUrl;
    }

    /**
     * Gets the service place url according to the https flag.
     *
     */
    public function getPlaceUrlDetails()
    {
        if ($this->isHttps()) {
            return str_replace('http://', 'https://', $this->placeUrlDetails);
        }

        return $this->placeUrlDetails;
    }

    /**
     * Sets the service url.
     *
     * @param string $url The service url.
     *
     */
    public function setPlaceUrlDetails($placeUrlDetails)
    {
        $this->placeUrlDetails = $placeUrlDetails;
    }

    /**
     * Get the type.
     *
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type.
     *
     * @param string $type The service type.
     *
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Checks if the service uses HTTPS.
     *
     * @return boolean TRUE if the service uses HTTPS else FALSE.
     */
    public function isHttps()
    {
        return $this->https;
    }

    /**
     * Sets the service HTTPS flag.
     *
     * @param boolean $https TRUE if the service uses HTTPS else FALSE.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the https flag is not valid.
     */
    public function setHttps($https)
    {
        if (!is_bool($https)) {
            throw GeocodingException::invalidGeocoderProviderHttps();
        }

        $this->https = $https;
    }

    /**
     * Gets the service format.
     *
     * @return string The service format.
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets the service format.
     *
     * @param string $format The service format.
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the format is not valid.
     */
    public function setFormat($format)
    {
        if (($format !== 'json') && ($format !== 'xml')) {
            throw GeocodingException::invalidGeocoderProviderFormat();
        }

        $this->format = $format;
    }

    /**
     * Gets the xml parser.
     *
     * @return \Ivory\GoogleMap\Services\Utils\XmlParser The xml parser.
     */
    public function getXmlParser()
    {
        return $this->xmlParser;
    }

    /**
     * Sets the xml parser.
     *
     * @param \Ivory\GoogleMap\Services\Geocoding\XmlParser $xmlParser The xml parser.
     */
    public function setXmlParser(XmlParser $xmlParser)
    {
        $this->xmlParser = $xmlParser;
    }

    /**
     * Checks if the geocoder provider has a business account.
     *
     * @return boolean TRUE if the geocoder provider has a business account else FALSE.
     */
    public function hasBusinessAccount()
    {
        return $this->businessAccount !== null;
    }

    /**
     * Gets the business account.
     *
     * @return \Ivory\GoogleMap\Services\BusinessAccount The business account.
     */
    public function getBusinessAccount()
    {
        return $this->businessAccount;
    }

    /**
     * Sets the business account.
     *
     * @param \Ivory\GoogleMap\Services\BusinessAccount $businessAccount The business account.
     */
    public function setBusinessAccount(BusinessAccount $businessAccount = null)
    {
        $this->businessAccount = $businessAccount;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ivory\GoogleMap\Exception\GeocodingException If the request is not valid.
     */
    public function getGeocodedData($request)
    {
        $geocoderRequest = new GeocoderRequest();

        if ($this->type == "place") {
            $geocoderRequest->setPlaceName($request);
        } else if (is_string($request)) {
            $geocoderRequest->setAddress($request);
        } elseif ($request instanceof GeocoderRequest) {
            $geocoderRequest = $request;
        } else {
            throw GeocodingException::invalidGeocoderProviderRequestArguments();
        }

        if (!$geocoderRequest->isValid()) {
            throw GeocodingException::invalidGeocoderProviderRequest();
        }

        $url = $this->generateUrl($geocoderRequest);
        $response = $this->getAdapter()->getContent($url);

        if ($response === null) {
            throw GeocodingException::invalidServiceResult();
        }

        $normalizedResponse = $this->parse($response);

        if ($this->getType() == "place") {
            $geocoderRequest->setPlaceId($this->getPlaceId($normalizedResponse));

            $url = $this->generateUrl($geocoderRequest);

            $responseDetail = $this->getAdapter()->getContent($url);

            $dataResponse = $this->parse($responseDetail);

            $response = $this->buildPlaceResponse($dataResponse);
        } else {
            $response = $this->buildGeocoderResponse($normalizedResponse);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getReversedData(array $coordinates)
    {
        $request = new GeocoderRequest();
        $request->setCoordinate($coordinates[0], $coordinates[1]);

        return $this->getGeocodedData($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ivory_google_map';
    }

    /**
     * Generates geocoding URL according to the request.
     *
     * @param \Ivory\GoogleMap\Services\Geocoding\GeocoderRequest $geocoderRequest The geocoder request.
     *
     * @return string The generated URL.
     */
    protected function generateUrl(GeocoderRequest $geocoderRequest)
    {
        $httpQuery = array();
        $apiUrl = $this->getUrl();

        if ($geocoderRequest->hasAddress()) {
            $httpQuery['address'] = $geocoderRequest->getAddress();
        } else if ($geocoderRequest->hasCoordinate()){
            $httpQuery['latlng'] = sprintf(
                '%s,%s',
                $geocoderRequest->getCoordinate()->getLatitude(),
                $geocoderRequest->getCoordinate()->getLongitude()
            );
        }

        if ($geocoderRequest->hasBound()) {
            $httpQuery['bound'] = sprintf(
                '%s,%s|%s,%s',
                $geocoderRequest->getBound()->getSouthWest()->getLatitude(),
                $geocoderRequest->getBound()->getSouthWest()->getLongitude(),
                $geocoderRequest->getBound()->getNorthEast()->getLatitude(),
                $geocoderRequest->getBound()->getNorthEast()->getLongitude()
            );
        }

        if ($geocoderRequest->hasRegion()) {
            $httpQuery['region'] = $geocoderRequest->getRegion();
        }

        if ($geocoderRequest->hasLanguage()) {
            $httpQuery['language'] = $geocoderRequest->getLanguage();
        }

        if ($geocoderRequest->hasPlaceId()) {
            $httpQuery['placeid'] = $geocoderRequest->getPlaceId();
        }

        if ($geocoderRequest->hasPlaceName() && !$geocoderRequest->hasPlaceId()) {
            $httpQuery['query'] = $geocoderRequest->getPlaceName();
        }

        if ($this->getType() == "default") {
            $httpQuery['sensor'] = $geocoderRequest->hasSensor() ? 'true' : 'false';
        } else if ($this->getType() == "place") {
            $httpQuery['key'] = $this->locale;

            if ($geocoderRequest->hasPlaceId()) {
                $apiUrl = $this->getPlaceUrlDetails();
            } else {
                $apiUrl = $this->getPlaceUrl();
            }
        }

        $url = sprintf('%s/%s?%s', $apiUrl, $this->getFormat(), http_build_query($httpQuery));

        return $this->signUrl($url);
    }

    /**
     * Sign an url for business account.
     *
     * @param string $url The url.
     *
     * @return string The signed url.
     */
    protected function signUrl($url)
    {
        if (!$this->hasBusinessAccount()) {
            return $url;
        }

        return $this->businessAccount->signUrl($url);
    }

    /**
     * Parses & normalizes the geocoding result response.
     *
     * @param string $response The response.
     *
     * @return \stdClass The parsed & normalized response.
     */
    protected function parse($response)
    {
        if ($this->format == 'json') {
            return $this->parseJSON($response);
        }

        return $this->parseXML($response);
    }

    /**
     * Parses & normalizes a JSON geocoding result response.
     *
     * @param string $response The response.
     *
     * @return \stdClass The parsed & normalized response.
     */
    protected function parseJSON($response)
    {
        return json_decode($response);
    }

    /**
     * Parses & normalizes an XML geocoding result response.
     *
     * @param string $response The response.
     *
     * @return \stdClass The parsed & normalized response.
     */
    protected function parseXML($response)
    {
        $rules = array(
            'address_component' => 'address_components',
            'type'              => 'types',
            'result'            => 'results',
        );

        return $this->xmlParser->parse($response, $rules);
    }

    /**
     * Builds the geocoder results accordint to a normalized geocoding results.
     *
     * @param \stdClass $geocoderResponse The normalized geocder response.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse The builded geocoder response.
     */
    protected function buildGeocoderResponse(\stdClass $geocoderResponse)
    {
        $results = array();
        foreach ($geocoderResponse->results as $geocoderResult) {
            $results[] = $this->buildGeocoderResult($geocoderResult);
        }

        $status = $geocoderResponse->status;

        return new GeocoderResponse($results, $status);
    }

    /**
     * Builds a geocoder result according to a normalized geocoding result.
     *
     * @param \stdClass $geocoderResult The normalized geocoder result.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResult The builded geocoder result.
     */
    protected function buildGeocoderResult(\stdClass $geocoderResult)
    {
        $addressComponents = $this->buildGeocoderAddressComponents($geocoderResult->address_components);
        $formattedAddress = $geocoderResult->formatted_address;
        $geometry = $this->buildGeocoderGeometry($geocoderResult->geometry);
        $types = $geocoderResult->types;
        $placeId = $geocoderResult->place_id;
        $partialMatch = isset($geocoderResult->partial_match) ? $geocoderResult->partial_match : null;

        return new GeocoderResult($addressComponents, $formattedAddress, $geometry, $types, $partialMatch, $placeId);
    }

    /**
     * Builds the gecoder address components according to a normalized geocoding address components.
     *
     * @param array $geocoderAddressComponents The normalized geocoder address components.
     *
     * @return array The builded geocoder address components.
     */
    protected function buildGeocoderAddressComponents(array $geocoderAddressComponents)
    {
        $results = array();

        foreach ($geocoderAddressComponents as $geocoderAddressComponent) {
            $results[] = $this->buildGeocoderAddressComponent($geocoderAddressComponent);
        }

        return $results;
    }

    /**
     * Builds a geocoder address component according to a normalized geocoding address component.
     *
     * @param \stdClass $geocoderAddressComponent The normalized geocoder address component.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderAddressComponent The builded geocoder address
     *                                                                             component.
     */
    protected function buildGeocoderAddressComponent(\stdClass $geocoderAddressComponent)
    {
        $longName = $geocoderAddressComponent->long_name;
        $shortName = $geocoderAddressComponent->short_name;
        $types = $geocoderAddressComponent->types;

        return new GeocoderAddressComponent($longName, $shortName, $types);
    }

    /**
     * Builds a geocoder geometry according to a normalized geocoding geometry.
     *
     * @param \stdClass $geocoderGeometry The normalized geocoder geomety.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderGeometry The builded geocoder geometry.
     */
    protected function buildGeocoderGeometry(\stdClass $geocoderGeometry)
    {
        $location = new Coordinate(
            $geocoderGeometry->location->lat,
            $geocoderGeometry->location->lng
        );

        if (isset($geocoderGeometry->location_type)) {
            $locationType = $geocoderGeometry->location_type;
        } else {
            $locationType = null;
        }

        $viewport = new Bound(
            new Coordinate($geocoderGeometry->viewport->southwest->lat, $geocoderGeometry->viewport->southwest->lng),
            new Coordinate($geocoderGeometry->viewport->northeast->lat, $geocoderGeometry->viewport->northeast->lng)
        );

        $bound = null;
        if (isset($geocoderGeometry->bounds)) {
            $bound = new Bound(
                new Coordinate($geocoderGeometry->bounds->southwest->lat, $geocoderGeometry->bounds->southwest->lng),
                new Coordinate($geocoderGeometry->bounds->northeast->lat, $geocoderGeometry->bounds->northeast->lng)
            );
        }

        return new GeocoderGeometry($location, $locationType, $viewport, $bound);
    }

    /**
     * Builds the gecoder address components according to a normalized geocoding address components.
     *
     * @param array $geocoderAddressComponents The normalized geocoder address components.
     *
     * @return array The builded geocoder address components.
     */
    protected function buildPlacePhotos(array $placePhotos)
    {
        $results = array();

        foreach ($placePhotos as $placePhoto) {
            $results[] = $this->buildPlacePhotosComponent($placePhoto);
        }

        return $results;
    }

    /**
     * Builds a geocoder address component according to a normalized geocoding address component.
     *
     * @param \stdClass $geocoderAddressComponent The normalized geocoder address component.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderAddressComponent The builded geocoder address
     *                                                                             component.
     */
    protected function buildPlacePhotosComponent(\stdClass $buildPlacePhotoComponent)
    {
        $height = $buildPlacePhotoComponent->height;
        $width = $buildPlacePhotoComponent->width;
        $htmlAttribution = $buildPlacePhotoComponent->html_attributions;
        $photoReference = $buildPlacePhotoComponent->photo_reference;

        return new PlacePhoto($height, $width, $htmlAttribution, $photoReference);
    }

    /**
     * Builds the gecoder address components according to a normalized geocoding address components.
     *
     * @param array $geocoderAddressComponents The normalized geocoder address components.
     *
     * @return array The builded geocoder address components.
     */
    protected function buildOpeningHours(\stdClass $placeResult)
    {
        $results = array();

        $openNow = null;

        if (isset($placeResult->open_now)) {
            $openNow = $placeResult->open_now;
        }

        foreach ($placeResult->periods as $openingHour) {
            $periods[] = $this->buildOpeningHoursComponent($openingHour);
        }

        return new PlaceOpeningHours($openNow, $periods, null);
    }

    /**
     * Builds a opening hours component according to a normalized opening hour component.
     *
     * @param \stdClass $openingHoursComponent The normalized opening hour component.
     *
     * @return PlacePhoto The builded place photo
     */
    protected function buildOpeningHoursComponent(\stdClass $openingHoursComponent)
    {
        $open = new PlaceOpenClose($openingHoursComponent->open->day, $openingHoursComponent->open->time);
        $close = new PlaceOpenClose($openingHoursComponent->close->day, $openingHoursComponent->close->time);

        return new PlacePeriods($open, $close);
    }

    /**
     * Builds the geocoder results accordint to a normalized geocoding results.
     *
     * @param \stdClass $geocoderResponse The normalized geocder response.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse The builded geocoder response.
     */
    protected function getPlaceId(\stdClass $placeResponse)
    {
        $results = array();
        $placeId = "";

        foreach ($placeResponse->results as $placeResult) {
            $placeId = $placeResult->place_id;
        }

        $status = $placeResponse->status;

        return $placeId;
    }

    /**
     * Builds the geocoder results accordint to a normalized geocoding results.
     *
     * @param \stdClass $geocoderResponse The normalized geocder response.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResponse The builded geocoder response.
     */
    protected function buildPlaceResponse(\stdClass $placeResponse)
    {
        $results = array();

        $results[] = $this->buildPlaceResult($placeResponse->result);

        $status = $placeResponse->status;

        return new PlaceResponse($results, $status);
    }

    /**
     * Builds a geocoder result according to a normalized geocoding result.
     *
     * @param \stdClass $geocoderResult The normalized geocoder result.
     *
     * @return \Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResult The builded geocoder result.
     */
    protected function buildPlaceResult(\stdClass $placeResult)
    {
        $addressComponents = null;
        $formattedAddress = null;
        $geometry = null;
        $types = null;
        $placeId = null;
        $icon = null;
        $id = null;
        $name = null;
        $photos = null;
        $reviews = null;
        $rating = null;
        $reference = null;
        $adrAddress = null;
        $formattedPhoneNumber = null;
        $internationalPhoneNumber = null;
        $url = null;
        $utcOffset = null;
        $vicinity = null;
        $website = null;
        $openingHours = null;
        
        if (isset($placeResult->address_components))
            $addressComponents = $this->buildGeocoderAddressComponents($placeResult->address_components);
        if (isset($placeResult->formatted_address))
            $formattedAddress = $placeResult->formatted_address;
        if (isset($placeResult->geometry))
            $geometry = $this->buildGeocoderGeometry($placeResult->geometry);
        if (isset($placeResult->types))
            $types = $placeResult->types;
        if (isset($placeResult->place_id))
            $placeId = $placeResult->place_id;
        if (isset($placeResult->icon))
            $icon = $placeResult->icon;
        if (isset($placeResult->id))
            $id = $placeResult->id;
        if (isset($placeResult->name))
            $name = $placeResult->name;
        if (isset($placeResult->photos))
            $photos = $this->buildPlacePhotos($placeResult->photos);
        if (isset($placeResult->reviews))
            $reviews = $placeResult->reviews;
        if (isset($placeResult->rating))
            $rating = $placeResult->rating;
        if (isset($placeResult->reference))
            $reference = $placeResult->reference;
        if (isset($placeResult->adr_address))
            $adrAddress = $placeResult->adr_address;
        if (isset($placeResult->formatted_phone_number))
            $formattedPhoneNumber = $placeResult->formatted_phone_number;
        if (isset($placeResult->international_phone_number))
            $internationalPhoneNumber = $placeResult->international_phone_number;
        if (isset($placeResult->url))
            $url = $placeResult->url;
        if (isset($placeResult->utc_offset))
            $utcOffset = $placeResult->utc_offset;
        if (isset($placeResult->vicinity))
            $vicinity = $placeResult->vicinity;
        if (isset($placeResult->website))
            $website = $placeResult->website;
        if (isset($placeResult->opening_hours))
            $openingHours = $this->buildOpeningHours($placeResult->opening_hours);

        return new PlaceResult($addressComponents, $formattedAddress, $geometry, $adrAddress, $formattedPhoneNumber,
                $icon, $id, $internationalPhoneNumber, $name, $photos, $placeId, $rating, $reference, $reviews,
                $url, $types, $utcOffset, $vicinity, $website, $openingHours);
    }
}
