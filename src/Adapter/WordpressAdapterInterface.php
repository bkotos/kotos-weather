<?php

namespace KotosWeather\Adapter;

use KotosWeather\Entity\Wordpress\MenuPage;
use KotosWeather\Entity\Wordpress\Setting;

interface WordpressAdapterInterface
{
    /**
     * @param callable $function
     */
    public function registerActivationHook(callable $function);

    /**
     * @param callable $function
     */
    public function registerDeactivationHook(callable $function);

    /**
     * @param callable $function
     */
    public function addAdminMenuAction(callable $function);

    /**
     * @param callable $function
     */
    public function addAdminEnqueueScriptsAction(callable $function);

    /**
     * @param callable $function
     */
    public function addWidgetsInitAction(callable $function);

    /**
     * @param string $actionName
     * @param callable $function
     */
    public function addUnauthenticatedAjaxAction(string $actionName, callable $function);

    /**
     * @param string|\WP_Widget $widget
     */
    public function registerWidget($widget);

    /**
     * @param callable $function
     */
    public function addPluginActionLinksFilter(callable $function);

    /**
     * @param string $handle
     * @param string $src
     */
    public function enqueueStyle(string $handle, string $src = '');

    /**
     * @param string $handle
     * @param string $src
     */
    public function enqueueScript(string $handle, string $src = '');

    /**
     * @param MenuPage $menuPage
     * @param callable $function
     */
    public function addMenuPage(MenuPage $menuPage, callable $function);

    /**
     * @param Setting $setting
     */
    public function registerSetting(Setting $setting);

    /**
     * @param Setting $setting
     */
    public function unregisterSetting(Setting $setting);

    /**
     * @param string $optionGroup
     */
    public function settingsFields(string $optionGroup);

    /**
     * @param string $page
     */
    public function doSettingsSections(string $page);

    /**
     * @param string $text
     * @return string
     */
    public function escapeAttribute(string $text): string;

    /**
     * @param string $text
     * @return string
     */
    public function escapeHtml(string $text): string;

    /**
     * @param string $text
     * @return string
     */
    public function escapeJs(string $text): string;

    /**
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $option, $default = false);

    /**
     * @param string|\WP_Error $message
     * @param string|int $title
     * @param string|array|int $args
     */
    public function wpDie($message = '', $title = '', $args = array());

    /**
     * @param int $code
     * @param string $description
     */
    public function statusHeader(int $code, string $description = '');
}