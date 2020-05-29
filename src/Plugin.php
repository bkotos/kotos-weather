<?php

namespace KotosWeather;

use KotosWeather\Adapter\WordpressAdapterInterface;

class Plugin
{
    const LOCALE_URI_PARAMETER = 'locale';

    const AJAX_GET_WEATHER_FORECAST_ACTION_NAME = 'get_forecast';

    const WIDGET_DEFAULT_FRONTEND_TITLE = 'Current Weather';

    const WIDGET_BASE_ID = 'kotos_weather_widget';

    const TEXT_DOMAIN = 'kotos_weather_widget_domain';

    const DESCRIPTION = 'Displays the weather for any given location.';

    const WIDGET_ADMIN_TITLE = 'Kotos Weather Widget';

    const KOTOS_WEATHER_OPTION_GROUP = 'kotos-weather-settings-group';

    /** @var WordpressAdapterInterface */
    private $wordpressAdapter;

    /** @var PluginHooks */
    private $pluginHooks;

    /**
     * Plugin constructor.
     * @param WordpressAdapterInterface $wordpressAdapter
     * @param PluginHooks $pluginHooks
     */
    public function __construct(WordpressAdapterInterface $wordpressAdapter, PluginHooks $pluginHooks)
    {
        $this->wordpressAdapter = $wordpressAdapter;
        $this->pluginHooks = $pluginHooks;
    }

    public function initialize()
    {
        $this->registerHooks();
    }

    public function registerHooks()
    {
        $this->wordpressAdapter->registerActivationHook([$this->pluginHooks, 'onActivateHook']);
        $this->wordpressAdapter->registerDeactivationHook([$this->pluginHooks, 'onDeactivateHook']);

        // plugin settings page
        $this->wordpressAdapter->addAdminMenuAction([$this->pluginHooks, 'onAdminMenuAction']);
        $this->wordpressAdapter->addAdminInitHook([$this->pluginHooks, 'onAdminInitHook']);
        $this->wordpressAdapter->addPluginActionLinksFilter([$this->pluginHooks, 'onPluginActionLinksFilter']);
        $this->wordpressAdapter->addAdminEnqueueScriptsAction([$this->pluginHooks, 'onEnqueueAdminScriptsAction']);

        // widget
        $this->wordpressAdapter->addWidgetsInitAction([$this->pluginHooks, 'onWidgetsInitAction']);
        $this->wordpressAdapter->addUnauthenticatedAjaxAction(
            self::AJAX_GET_WEATHER_FORECAST_ACTION_NAME,
            [$this->pluginHooks, 'onWidgetGetWeatherForecastAjaxAction']
        );
    }
}
