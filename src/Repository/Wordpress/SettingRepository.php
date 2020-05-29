<?php

namespace KotosWeather\Repository\Wordpress;

use KotosWeather\Adapter\WordpressAdapterInterface;
use KotosWeather\Entity\Wordpress\Setting;
use KotosWeather\Plugin;

class SettingRepository
{
    const EMPTY_VALUE = '';

    const DEFAULT_API_HOST = 'https://api.openweathermap.org';

    const DEFAULT_ASSET_HOST = 'http://openweathermap.org/';

    const DEFAULT_API_KEY = 'f7cf53c05e2aa428bbd6176db47633c7';

    /** @var WordpressAdapterInterface */
    private $wordpressAdapter;

    /**
     * SettingRepository constructor.
     * @param WordpressAdapterInterface $wordpressAdapter
     */
    public function __construct(WordpressAdapterInterface $wordpressAdapter)
    {
        $this->wordpressAdapter = $wordpressAdapter;
    }

    /**
     * @return Setting[]
     */
    public function getAllSettings(): array
    {
        return [
            $this->getOpenWeatherMapAssetHostSetting(),
            $this->getOpenWeatherMapApiHostSetting(),
            $this->getOpenWeatherMapApiKeySetting()
        ];
    }

    public function getOpenWeatherMapAssetHostSetting(): Setting
    {
        $openWeatherMapApiHostnameSetting = new Setting();
        $openWeatherMapApiHostnameSetting
            ->setOptionGroup(Plugin::KOTOS_WEATHER_OPTION_GROUP)
            ->setOptionName('open_weather_map_asset_host')
            ->setLabel('OpenWeatherMap Asset Host')
            ->setDefaultValue(self::DEFAULT_ASSET_HOST);

        $this->fetchSettingValue($openWeatherMapApiHostnameSetting);

        return $openWeatherMapApiHostnameSetting;
    }

    public function getOpenWeatherMapApiHostSetting(): Setting
    {
        $openWeatherMapApiHostnameSetting = new Setting();
        $openWeatherMapApiHostnameSetting
            ->setOptionGroup(Plugin::KOTOS_WEATHER_OPTION_GROUP)
            ->setOptionName('open_weather_map_api_host')
            ->setLabel('OpenWeatherMap API Host')
            ->setDefaultValue(self::DEFAULT_API_HOST);

        $this->fetchSettingValue($openWeatherMapApiHostnameSetting);

        return $openWeatherMapApiHostnameSetting;
    }

    public function getOpenWeatherMapApiKeySetting(): Setting
    {
        $openWeatherMapApiKeySetting = new Setting();
        $openWeatherMapApiKeySetting
            ->setOptionGroup(Plugin::KOTOS_WEATHER_OPTION_GROUP)
            ->setOptionName('open_weather_map_api_key')
            ->setLabel('OpenWeatherMap API Key')
            ->setDefaultValue(self::DEFAULT_API_KEY);

        $this->fetchSettingValue($openWeatherMapApiKeySetting);

        return $openWeatherMapApiKeySetting;
    }

    private function fetchSettingValue(Setting $setting)
    {
        $optionName = $setting->getOptionName();
        $defaultValue = $setting->getDefaultValue();
        $value = trim($this->wordpressAdapter->getOption($optionName, $defaultValue));

        if (empty($value)) {
            if (!empty($defaultValue)) {
                $value = $defaultValue;
            } else {
                $value = self::EMPTY_VALUE;
            }
        }

        $setting->setValue($value);
    }
}
