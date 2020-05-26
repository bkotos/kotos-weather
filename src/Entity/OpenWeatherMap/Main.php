<?php

namespace KotosWeather\Entity\OpenWeatherMap;

class Main
{
    /** @var float */
    private $temp;

    /** @var float */
    private $feels_like;

    /** @var float */
    private $temp_min;

    /** @var float */
    private $temp_max;

    /**
     * @return float
     */
    public function getTemp(): float
    {
        return $this->temp;
    }

    /**
     * @param float $temp
     * @return Main
     */
    public function setTemp(float $temp): Main
    {
        $this->temp = $temp;
        return $this;
    }

    /**
     * @return float
     */
    public function getFeelsLike(): float
    {
        return $this->feels_like;
    }

    /**
     * @param float $feels_like
     * @return Main
     */
    public function setFeelsLike(float $feels_like): Main
    {
        $this->feels_like = $feels_like;
        return $this;
    }

    /**
     * @return float
     */
    public function getTempMin(): float
    {
        return $this->temp_min;
    }

    /**
     * @param float $temp_min
     * @return Main
     */
    public function setTempMin(float $temp_min): Main
    {
        $this->temp_min = $temp_min;
        return $this;
    }

    /**
     * @return float
     */
    public function getTempMax(): float
    {
        return $this->temp_max;
    }

    /**
     * @param float $temp_max
     * @return Main
     */
    public function setTempMax(float $temp_max): Main
    {
        $this->temp_max = $temp_max;
        return $this;
    }
}
