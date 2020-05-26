<?php

namespace KotosWeather\Entity\OpenWeatherMap;

class Weather
{
    /** @var string */
    private $main;

    /** @var string */
    private $icon;

    /**
     * @return string
     */
    public function getMain(): string
    {
        return $this->main;
    }

    /**
     * @param string $main
     * @return Weather
     */
    public function setMain(string $main): Weather
    {
        $this->main = $main;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return Weather
     */
    public function setIcon(string $icon): Weather
    {
        $this->icon = $icon;
        return $this;
    }
}
