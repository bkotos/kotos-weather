<?php

namespace KotosWeather;

use KotosWeather\Adapter\PhpStandardLibraryAdapterInterface;
use KotosWeather\Adapter\WordpressAdapterInterface;
use KotosWeather\Entity\Wordpress\MenuPage;
use KotosWeather\Repository\Wordpress\SettingRepository;
use KotosWeather\Service\WeatherService;
use KotosWeather\Template\TemplateInterface;
use KotosWeather\Widget\KotosWeatherWidget;
use Symfony\Component\Serializer\Serializer;

class PluginHooks
{
    /** @var WordpressAdapterInterface */
    private $wordpressAdapter;

    /** @var PhpStandardLibraryAdapterInterface */
    private $phpStandardLibraryAdapter;

    /** @var MenuPage */
    private $pluginSettingsPage;

    /** @var SettingRepository */
    private $settingRepository;

    /** @var WeatherService */
    private $weatherService;

    /** @var Serializer */
    private $serializer;

    /** @var TemplateInterface */
    private $pluginSettingsPageTemplate;

    /**
     * PluginEvents constructor.
     * @param WordpressAdapterInterface $wordpressAdapter
     * @param PhpStandardLibraryAdapterInterface $phpStandardLibraryAdapter
     * @param MenuPage $pluginSettingsPage
     * @param SettingRepository $settingRepository
     * @param WeatherService $weatherService
     * @param Serializer $serializer
     * @param TemplateInterface $pluginSettingsPageTemplate
     */
    public function __construct(WordpressAdapterInterface $wordpressAdapter, PhpStandardLibraryAdapterInterface $phpStandardLibraryAdapter, MenuPage $pluginSettingsPage, SettingRepository $settingRepository, WeatherService $weatherService, Serializer $serializer, TemplateInterface $pluginSettingsPageTemplate)
    {
        $this->wordpressAdapter = $wordpressAdapter;
        $this->phpStandardLibraryAdapter = $phpStandardLibraryAdapter;
        $this->pluginSettingsPage = $pluginSettingsPage;
        $this->settingRepository = $settingRepository;
        $this->weatherService = $weatherService;
        $this->serializer = $serializer;
        $this->pluginSettingsPageTemplate = $pluginSettingsPageTemplate;
    }

    public function onActivateHook()
    {
        $this->registerSettings();
    }

    public function onDeactivateHook()
    {
        $this->unregisterSettings();
    }

    public function onAdminMenuAction()
    {
        $this->wordpressAdapter->addMenuPage(
            $this->pluginSettingsPage,
            [$this->pluginSettingsPageTemplate, 'render']
        );
    }

    public function onAdminInitHook()
    {
        $this->registerSettings();
    }

    /**
     * @param string[] $links
     * @return string[]
     */
    public function onPluginActionLinksFilter(array $links): array
    {
        $links[] = "<a href=\"admin.php?page={$this->pluginSettingsPage->getMenuSlug()}\">Settings</a>";
        $links[] = "<a href=\"widgets.php\">Widgets</a>";

        return $links;
    }

    public function onEnqueueAdminScriptsAction()
    {
        $this->wordpressAdapter->enqueueStyle('settings-page-js', '/assets/css/settingsPage.css');
        $this->wordpressAdapter->enqueueScript('jQuery');
        $this->wordpressAdapter->enqueueScript('settings-page-js', '/assets/js/settingsPage.js');
    }

    public function onWidgetsInitAction()
    {
        $this->wordpressAdapter->registerWidget(new KotosWeatherWidget($this->wordpressAdapter));
    }

    public function onWidgetGetWeatherForecastAjaxAction()
    {
        try {
            $locale = $this->phpStandardLibraryAdapter->getQueryParameter(Plugin::LOCALE_URI_PARAMETER);
            $weatherForecast = $this->weatherService->getWeatherForecast($locale);
            $this->phpStandardLibraryAdapter->echo(
                $this->serializer->serialize($weatherForecast, 'json')
            );
            $this->wordpressAdapter->statusHeader(200);
        } catch (\Exception $e) {
            $this->phpStandardLibraryAdapter->errorLog($e->getMessage());
            $this->phpStandardLibraryAdapter->echo(
                $this->serializer->serialize(['success' => false], 'json')
            );
            $this->wordpressAdapter->statusHeader(400);
        }
        $this->wordpressAdapter->wpDie();
    }

    private function registerSettings()
    {
        $settings = $this->settingRepository->getAllSettings();
        foreach ($settings as $setting) {
            $this->wordpressAdapter->registerSetting($setting);
        }
    }

    private function unregisterSettings()
    {
        $settings = $this->settingRepository->getAllSettings();
        foreach ($settings as $setting) {
            $this->wordpressAdapter->unregisterSetting($setting);
        }
    }
}
