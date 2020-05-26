<?php

namespace KotosWeather\Service;

use GuzzleHttp\Exception\GuzzleException;
use KotosWeather\Client\OpenWeatherMapHttpClient;
use KotosWeather\Entity\WeatherForecast;
use KotosWeather\Exception\OpenWeatherMapApiException;
use KotosWeather\Repository\Wordpress\SettingRepository;

class WeatherService
{
    const DATE_FORMAT = 'l, F j';

    const TIME_FORMAT = 'h:i a';

    const EMPTY_FORECASE_PLACEHOLDER = '-';

    const DEFAULT_ICON = '10d';

    /** @var OpenWeatherMapHttpClient */
    private $openWeatherMapHttpClient;

    /** @var SettingRepository */
    private $settingRepository;

    /**
     * WeatherService constructor.
     * @param OpenWeatherMapHttpClient $openWeatherMapHttpClient
     * @param SettingRepository $settingRepository
     */
    public function __construct(OpenWeatherMapHttpClient $openWeatherMapHttpClient, SettingRepository $settingRepository)
    {
        $this->openWeatherMapHttpClient = $openWeatherMapHttpClient;
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param string $locale
     * @return WeatherForecast
     * @throws OpenWeatherMapApiException
     * @throws GuzzleException
     */
    public function getWeatherForecast(string $locale): WeatherForecast
    {
        $apiHost = $this->settingRepository->getOpenWeatherMapApiHostSetting()->getValue();
        $apiKey = $this->settingRepository->getOpenWeatherMapApiKeySetting()->getValue();
        $apiResponse = $this->openWeatherMapHttpClient->getWeatherByCityNameUsingApiKey($locale, $apiHost, $apiKey);

        $timestamp = $apiResponse->getDt();
        $offset = $apiResponse->getTimezone();
        $dateTime = $this->buildDateTimeFromTimestampAndOffset($timestamp, $offset);

        $weather = $apiResponse->getWeather();
        if (count($weather) > 0) {
            $forecastLabel = $weather[0]->getMain();
            $forecastIconUrl = $this->getForecastIconUrl($weather[0]->getIcon());
        } else {
            $forecastLabel = self::EMPTY_FORECASE_PLACEHOLDER;
            $forecastIconUrl = $this->getForecastIconUrl(self::DEFAULT_ICON);
        }

        // get temperature scale and do conversion from kelvin to the desired scale
        $actualTemperature = $apiResponse->getMain()->getTemp();
        $feelsLikeTemperature = $apiResponse->getMain()->getFeelsLike();
        $minTemperature = $apiResponse->getMain()->getTempMin();
        $maxTemperature = $apiResponse->getMain()->getTempMax();

        $weatherForecast = new WeatherForecast();
        $weatherForecast
            ->setLocaleName($apiResponse->getName())
            ->setTimestamp($dateTime->getTimestamp())
            ->setFormattedDate($dateTime->format(self::DATE_FORMAT))
            ->setFormattedTime($dateTime->format(self::TIME_FORMAT))
            ->setForecastLabel($forecastLabel)
            ->setForecastIconUrl($forecastIconUrl)
            ->setActualTemperature($actualTemperature)
            ->setFeelsLikeTemperature($feelsLikeTemperature)
            ->setMinTemperature($minTemperature)
            ->setMaxTemperature($maxTemperature);

        return $weatherForecast;
    }

    private function getForecastIconUrl(string $icon)
    {
        $assetHostSetting = $this->settingRepository->getOpenWeatherMapAssetHostSetting();
        $host = $assetHostSetting->getValue();

        return "{$host}/img/wn/{$icon}@2x.png";
    }

    /**
     * @param int $timestamp
     * @param int $offsetInSeconds
     * @return string
     * @throws \Exception
     */
    private function buildDateTimeFromTimestampAndOffset(int $timestamp, int $offsetInSeconds): \DateTime
    {
        $absoluteOffsetInSeconds = abs($offsetInSeconds);

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($timestamp);
        $dateInterval = new \DateInterval("PT{$absoluteOffsetInSeconds}S");

        if ($offsetInSeconds >= 0) {
            $dateTime->add($dateInterval);
        } else {
            $dateTime->sub($dateInterval);
        }

        return $dateTime;
    }
}
