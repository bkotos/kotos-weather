<?php

namespace KotosWeather\Adapter;

use KotosWeather\Entity\Wordpress\MenuPage;
use KotosWeather\Entity\Wordpress\Setting;

class WordpressAdapter implements WordpressAdapterInterface
{
    /** @var string */
    private $pluginFilename;

    /** @var string */
    private $pluginDirectory;

    /** @var string */
    private $pluginBasename;

    /**
     * WordpressAdapter constructor.
     * @param string $pluginFilename
     * @param string $pluginDirectory
     * @param string $pluginName
     */
    public function __construct(string $pluginFilename, string $pluginDirectory, string $pluginName)
    {
        $this->pluginFilename = $pluginFilename;
        $this->pluginDirectory = $pluginDirectory;
        $this->pluginBasename = $pluginName;
    }

    /**
     * @param callable $function
     */
    public function registerActivationHook(callable $function)
    {
        \register_activation_hook($this->pluginFilename, $function);
    }

    /**
     * @param callable $function
     */
    public function registerDeactivationHook(callable $function)
    {
        \register_deactivation_hook($this->pluginFilename, $function);
    }

    /**
     * @param callable $function
     */
    public function addAdminMenuAction(callable $function)
    {
        \add_action('admin_menu', $function);
    }

    /**
     * @param callable $function
     */
    public function addAdminEnqueueScriptsAction(callable $function)
    {
        \add_action('admin_enqueue_scripts', $function);
    }

    /**
     * @param callable $function
     */
    public function addWidgetsInitAction(callable $function)
    {
        \add_action('widgets_init', $function);
    }

    /**
     * @param string $actionName
     * @param callable $function
     */
    public function addUnauthenticatedAjaxAction(string $actionName, callable $function)
    {
        \add_action("wp_ajax_{$actionName}", $function);
        \add_action("wp_ajax_nopriv_{$actionName}", $function);
    }

    /**
     * @param string|\WP_Widget $widget
     */
    public function registerWidget($widget)
    {
        \register_widget($widget);
    }

    /**
     * @param callable $function
     */
    public function addPluginActionLinksFilter(callable $function)
    {
        \add_filter("plugin_action_links_{$this->pluginBasename}", $function);
    }

    /**
     * @param string $handle
     * @param string $src
     */
    public function enqueueStyle(string $handle, string $src = '')
    {
        \wp_enqueue_style($handle, plugins_url($src, $this->pluginFilename));
    }

    /**
     * @param string $handle
     * @param string $src
     */
    public function enqueueScript(string $handle, string $src = '')
    {
        \wp_enqueue_script($handle, plugins_url($src, $this->pluginFilename));
    }

    /**
     * @param MenuPage $menuPage
     * @param callable $function
     */
    public function addMenuPage(MenuPage $menuPage, callable $function) {
        \add_menu_page(
            $menuPage->getPageTitle(),
            $menuPage->getMenuTitle(),
            $menuPage->getCapability(),
            $menuPage->getMenuSlug(),
            $function,
            $menuPage->getIconUrl(),
            $menuPage->getPosition()
        );
    }

    /**
     * @param Setting $setting
     */
    public function registerSetting(Setting $setting)
    {
        \register_setting($setting->getOptionGroup(), $setting->getOptionName());
    }

    /**
     * @param Setting $setting
     */
    public function unregisterSetting(Setting $setting)
    {
        \unregister_setting($setting->getOptionGroup(), $setting->getOptionName());
    }

    /**
     * @param string $optionGroup
     */
    public function settingsFields(string $optionGroup)
    {
        \settings_fields($optionGroup);
    }

    /**
     * @param string $page
     */
    public function doSettingsSections(string $page)
    {
        \do_settings_sections($page);
    }

    /**
     * @param string $text
     * @return string
     */
    public function escapeAttribute(string $text): string
    {
        return \esc_attr($text);
    }

    /**
     * @param string $text
     * @return string
     */
    public function escapeHtml(string $text): string
    {
        return \esc_html($text);
    }

    /**
     * @param string $text
     * @return string
     */
    public function escapeJs(string $text): string
    {
        return \esc_js($text);
    }

    /**
     * @param string $option
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $option, $default = false)
    {
        return \get_option($option, $default);
    }

    /**
     * @param string|\WP_Error $message
     * @param string|int $title
     * @param string|array|int $args
     */
    public function wpDie($message = '', $title = '', $args = array())
    {
        \wp_die();
    }

    /**
     * @param int $code
     * @param string $description
     */
    public function statusHeader(int $code, string $description = '')
    {
        \status_header($code, $description);
    }
}
