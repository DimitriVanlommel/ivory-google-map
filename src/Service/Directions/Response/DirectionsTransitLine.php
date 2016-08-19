<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Service\Directions\Response;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class DirectionsTransitLine
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $shortName;

    /**
     * @var string|null
     */
    private $color;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var string|null
     */
    private $icon;

    /**
     * @var string|null
     */
    private $textColor;

    /**
     * @var DirectionsTransitVehicle|null
     */
    private $vehicle;

    /**
     * @var DirectionsTransitAgency[]
     */
    private $agencies = [];

    /**
     * @return bool
     */
    public function hasName()
    {
        return $this->name !== null;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function hasShortName()
    {
        return $this->shortName !== null;
    }

    /**
     * @return string|null
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string|null $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return bool
     */
    public function hasColor()
    {
        return $this->color !== null;
    }

    /**
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return bool
     */
    public function hasUrl()
    {
        return $this->url !== null;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function hasIcon()
    {
        return $this->icon !== null;
    }

    /**
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return bool
     */
    public function hasTextColor()
    {
        return $this->textColor !== null;
    }

    /**
     * @return string|null
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param string|null $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @return bool
     */
    public function hasVehicle()
    {
        return $this->vehicle !== null;
    }

    /**
     * @return DirectionsTransitVehicle|null
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param DirectionsTransitVehicle|null $vehicle
     */
    public function setVehicle(DirectionsTransitVehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;
    }

    /**
     * @return bool
     */
    public function hasAgencies()
    {
        return !empty($this->agencies);
    }

    /**
     * @return DirectionsTransitAgency[]
     */
    public function getAgencies()
    {
        return $this->agencies;
    }

    /**
     * @param DirectionsTransitAgency[] $agencies
     */
    public function setAgencies(array $agencies)
    {
        $this->agencies = $agencies;
        $this->addAgencies($agencies);
    }

    /**
     * @param DirectionsTransitAgency[] $agencies
     */
    public function addAgencies(array $agencies)
    {
        foreach ($agencies as $agency) {
            $this->addAgency($agency);
        }
    }

    /**
     * @param DirectionsTransitAgency $agency
     *
     * @return bool
     */
    public function hasAgency(DirectionsTransitAgency $agency)
    {
        return in_array($agency, $this->agencies, true);
    }

    /**
     * @param DirectionsTransitAgency $agency
     */
    public function addAgency(DirectionsTransitAgency $agency)
    {
        if (!$this->hasAgency($agency)) {
            $this->agencies[] = $agency;
        }
    }

    /**
     * @param DirectionsTransitAgency $agency
     */
    public function removeAgency(DirectionsTransitAgency $agency)
    {
        unset($this->agencies[array_search($agency, $this->agencies, true)]);
        $this->agencies = array_values($this->agencies);
    }
}