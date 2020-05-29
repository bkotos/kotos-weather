<?php

namespace KotosWeather\Service;

use GuzzleHttp\Client as GuzzleClient;
use KotosWeather\Adapter\PhpStandardLibraryAdapter;
use KotosWeather\Adapter\PhpStandardLibraryAdapterInterface;
use KotosWeather\Adapter\WordpressAdapter;
use KotosWeather\Adapter\WordpressAdapterInterface;
use KotosWeather\Client\OpenWeatherMapHttpClient;
use KotosWeather\Entity\Wordpress\MenuPage;
use KotosWeather\Plugin;
use KotosWeather\PluginHooks;
use KotosWeather\Repository\Wordpress\SettingRepository;
use KotosWeather\Template\PluginSettingsPageTemplate;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['pluginSettingsPage'] = function (Container $container) {
            $pluginSettingsPage = new MenuPage();
            $pluginSettingsPage
                ->setPageTitle('Kotos Weather')
                ->setMenuTitle('Kotos Weather')
                ->setCapability('manage_options')
                ->setMenuSlug('kotos_weather')
                ->setIconUrl('dashicons-admin-site')
                ->setPosition(110);

            return $pluginSettingsPage;
        };

        $container[GuzzleClient::class] = function (Container $container) {
            return new GuzzleClient();
        };

        $container[WordpressAdapterInterface::class] = function (Container $container) {
            $pluginFilename = realpath(__DIR__ . '/../../kotos-weather.php');
            $pluginDirectory = \plugin_dir_path($pluginFilename);
            $pluginBasename = \plugin_basename($pluginFilename);

            return new WordpressAdapter($pluginFilename, $pluginDirectory, $pluginBasename);
        };

        $container[PhpStandardLibraryAdapterInterface::class] = function (Container $container) {
            return new PhpStandardLibraryAdapter();
        };

        $container[OpenWeatherMapHttpClient::class] = function (Container $container) {
            return new OpenWeatherMapHttpClient(
                $container[GuzzleClient::class],
                $container[Serializer::class]
            );
        };

        $container[PluginSettingsPageTemplate::class] = function (Container $container) {
            return new PluginSettingsPageTemplate(
                $container[WordpressAdapterInterface::class],
                $container[SettingRepository::class]
            );
        };

        $container[SettingRepository::class] = function (Container $container) {
            return new SettingRepository(
                $container[WordpressAdapterInterface::class]
            );
        };

        $container[WeatherService::class] = function (Container $container) {
            return new WeatherService(
                $container[OpenWeatherMapHttpClient::class],
                $container[SettingRepository::class]
            );
        };

        $container[Serializer::class] = function (Container $container) {
            $phpDocExtractor = new PhpDocExtractor();
            $arrayDenormalizer = new ArrayDenormalizer();
            $normalizer = new ObjectNormalizer(null, null, null, $phpDocExtractor);
            $jsonEncoder = new JsonEncoder();

            return new Serializer([$normalizer, $arrayDenormalizer], [$jsonEncoder]);
        };

        $container[PluginHooks::class] = function (Container $container) {
            return new PluginHooks(
                $container[WordpressAdapterInterface::class],
                $container[PhpStandardLibraryAdapterInterface::class],
                $container['pluginSettingsPage'],
                $container[SettingRepository::class],
                $container[WeatherService::class],
                $container[Serializer::class],
                $container[PluginSettingsPageTemplate::class]
            );
        };

        $container[Plugin::class] = function (Container $container) {
            return new Plugin(
                $container[WordpressAdapterInterface::class],
                $container[PluginHooks::class]
            );
        };
    }
}
