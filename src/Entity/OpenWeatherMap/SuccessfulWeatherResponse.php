<?php

namespace KotosWeather\Entity\OpenWeatherMap;

class SuccessfulWeatherResponse
{
    /** @var Weather[] */
    private $weather = [];

    /** @var Main */
    private $main;

    /** @var int */
    private $dt;

    /** @var int */
    private $timezone;

    /** @var string */
    private $name;

    /**
     * @return Weather[]
     */
    public function getWeather(): array
    {
        return $this->weather;
    }

    /**
     * @param Weather[] $weather
     * @return SuccessfulWeatherResponse
     */
    public function setWeather(array $weather): SuccessfulWeatherResponse
    {
        $this->weather = $weather;
        return $this;
    }

    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }

    /**
     * @param Main $main
     * @return SuccessfulWeatherResponse
     */
    public function setMain(Main $main): SuccessfulWeatherResponse
    {
        $this->main = $main;
        return $this;
    }

    /**
     * @return int
     */
    public function getDt(): int
    {
        return $this->dt;
    }

    /**
     * @param int $dt
     * @return SuccessfulWeatherResponse
     */
    public function setDt(int $dt): SuccessfulWeatherResponse
    {
        $this->dt = $dt;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimezone(): int
    {
        return $this->timezone;
    }

    /**
     * @param int $timezone
     * @return SuccessfulWeatherResponse
     */
    public function setTimezone(int $timezone): SuccessfulWeatherResponse
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SuccessfulWeatherResponse
     */
    public function setName(string $name): SuccessfulWeatherResponse
    {
        $this->name = $name;
        return $this;
    }
}
