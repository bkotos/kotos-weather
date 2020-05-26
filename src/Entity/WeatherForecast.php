<?php

namespace KotosWeather\Entity;

class WeatherForecast
{
    /** @var string */
    private $localeName;

    /** @var int */
    private $timestamp;

    /** @var string */
    private $formattedDate;

    /** @var string */
    private $formattedTime;

    /** @var string */
    private $forecastLabel;

    /** @var string */
    private $forecastIconUrl;

    /** @var float */
    private $actualTemperature;

    /** @var float */
    private $feelsLikeTemperature;

    /** @var float */
    private $minTemperature;

    /** @var float */
    private $maxTemperature;

    /**
     * @return string
     */
    public function getLocaleName(): string
    {
        return $this->localeName;
    }

    /**
     * @param string $localeName
     * @return WeatherForecast
     */
    public function setLocaleName(string $localeName): WeatherForecast
    {
        $this->localeName = $localeName;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return WeatherForecast
     */
    public function setTimestamp(int $timestamp): WeatherForecast
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormattedDate(): string
    {
        return $this->formattedDate;
    }

    /**
     * @param string $formattedDate
     * @return WeatherForecast
     */
    public function setFormattedDate(string $formattedDate): WeatherForecast
    {
        $this->formattedDate = $formattedDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormattedTime(): string
    {
        return $this->formattedTime;
    }

    /**
     * @param string $formattedTime
     * @return WeatherForecast
     */
    public function setFormattedTime(string $formattedTime): WeatherForecast
    {
        $this->formattedTime = $formattedTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getForecastLabel(): string
    {
        return $this->forecastLabel;
    }

    /**
     * @param string $forecastLabel
     * @return WeatherForecast
     */
    public function setForecastLabel(string $forecastLabel): WeatherForecast
    {
        $this->forecastLabel = $forecastLabel;
        return $this;
    }

    /**
     * @return string
     */
    public function getForecastIconUrl(): string
    {
        return $this->forecastIconUrl;
    }

    /**
     * @param string $forecastIconUrl
     * @return WeatherForecast
     */
    public function setForecastIconUrl(string $forecastIconUrl): WeatherForecast
    {
        $this->forecastIconUrl = $forecastIconUrl;
        return $this;
    }

    /**
     * @return float
     */
    public function getActualTemperature(): float
    {
        return $this->actualTemperature;
    }

    /**
     * @param float $actualTemperature
     * @return WeatherForecast
     */
    public function setActualTemperature(float $actualTemperature): WeatherForecast
    {
        $this->actualTemperature = $actualTemperature;
        return $this;
    }

    /**
     * @return float
     */
    public function getFeelsLikeTemperature(): float
    {
        return $this->feelsLikeTemperature;
    }

    /**
     * @param float $feelsLikeTemperature
     * @return WeatherForecast
     */
    public function setFeelsLikeTemperature(float $feelsLikeTemperature): WeatherForecast
    {
        $this->feelsLikeTemperature = $feelsLikeTemperature;
        return $this;
    }

    /**
     * @return float
     */
    public function getMinTemperature(): float
    {
        return $this->minTemperature;
    }

    /**
     * @param float $minTemperature
     * @return WeatherForecast
     */
    public function setMinTemperature(float $minTemperature): WeatherForecast
    {
        $this->minTemperature = $minTemperature;
        return $this;
    }

    /**
     * @return float
     */
    public function getMaxTemperature(): float
    {
        return $this->maxTemperature;
    }

    /**
     * @param float $maxTemperature
     * @return WeatherForecast
     */
    public function setMaxTemperature(float $maxTemperature): WeatherForecast
    {
        $this->maxTemperature = $maxTemperature;
        return $this;
    }
}
