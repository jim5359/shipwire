<?php

namespace Shipwire\Model;

/**
 * Class Address
 * Abstract model class representing an object containing an address.
 * @package shipwire\Model
 */
abstract Class Address extends Model
{
    const GEO_CODE_URL = 'http://maps.googleapis.com/maps/api/geocode/json';
    /**
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * @param string $streetAddress
     * @return Address
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Address
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getFullAddress()
    {
        return $this->streetAddress . ',' . $this->city . ',' . $this->state . ',' . $this->postalCode;
    }

    public function geoCodeAddress()
    {
        $url = self::GEO_CODE_URL . '?address=' . urlencode($this->getFullAddress());
        $jsonString = file_get_contents($url);
        $json = json_decode($jsonString);

        $this->longitude = $json->results[0]->geometry->location->lng;
        $this->latitude = $json->results[0]->geometry->location->lat;
    }

    /**
     * Bind properties to a passed PDO Statement
     * @param \PDOStatement $statement
     * @return Address
     */
    public function bindParams($statement)
    {
        $statement->bindParam(':streetAddress', $this->streetAddress);
        $statement->bindParam(':city', $this->city);
        $statement->bindParam(':state', $this->state);
        $statement->bindParam(':postalCode', $this->postalCode);
        $statement->bindParam(':longitude', $this->longitude);
        $statement->bindParam(':latitude', $this->latitude);
        return $this;
    }

}