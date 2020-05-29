<?php

namespace KotosWeather\Template;

use KotosWeather\Adapter\WordpressAdapterInterface;
use KotosWeather\Entity\Wordpress\DropdownSetting;
use KotosWeather\Entity\Wordpress\Setting;
use KotosWeather\Plugin;
use KotosWeather\Repository\Wordpress\SettingRepository;

class PluginSettingsPageTemplate implements TemplateInterface
{
    /** @var WordpressAdapterInterface */
    private $wordpressAdapter;

    /** @var SettingRepository */
    private $settingRepository;

    /**
     * PluginSettingsPageTemplate constructor.
     * @param WordpressAdapterInterface $wordpressAdapter
     * @param SettingRepository $settingRepository
     */
    public function __construct(WordpressAdapterInterface $wordpressAdapter, SettingRepository $settingRepository)
    {
        $this->wordpressAdapter = $wordpressAdapter;
        $this->settingRepository = $settingRepository;
    }

    public function render()
    {
        /** @var Setting[]|DropdownSetting[] $settings */
        $settings = $this->settingRepository->getAllSettings();

        ?>
        <h1>Kotos Weather</h1>

        <form id="frm-kotos-weather" method="post" action="options.php">
            <?php $this->wordpressAdapter->settingsFields(Plugin::KOTOS_WEATHER_OPTION_GROUP); ?>
            <?php $this->wordpressAdapter->doSettingsSections(Plugin::KOTOS_WEATHER_OPTION_GROUP); ?>
            <table class="form-table">
            <?php
            foreach ($settings as $setting) {
                $optionName = $setting->getOptionName();
                $defaultValue = $setting->getDefaultValue();
                $value = $setting->getValue();
                $label = $setting->getLabel();
                $id = "txt-$optionName";
            ?>
                <tr valign="top">
                    <th scope="row">
                        <label for="<?php echo $this->wordpressAdapter->escapeAttribute($id); ?>">
                            <?php echo $this->wordpressAdapter->escapeHtml($label); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        if ($setting instanceof DropdownSetting) {
                        ?>
                        <select
                            class="setting-input sel-setting"
                            name="<?php echo $this->wordpressAdapter->escapeAttribute($optionName); ?>"
                            id="<?php echo $this->wordpressAdapter->escapeAttribute($id); ?>"
                            data-default-value="<?php echo $this->wordpressAdapter->escapeAttribute($defaultValue); ?>"
                        >
                            <?php
                            foreach ($setting->getValueDictionary() as $currentValue => $label) {
                                $isSelected = $value === $currentValue;
                                ?>
                                <option
                                    value="<?php echo $this->wordpressAdapter->escapeAttribute($currentValue); ?>"
                                    <?php echo $isSelected ? 'selected' : '' ?>
                                >
                                    <?php echo $this->wordpressAdapter->escapeHtml($label); ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php
                        } else {
                        ?>
                        <input
                            type="text"
                            class="setting-input sel-setting"
                            name="<?php echo $this->wordpressAdapter->escapeAttribute($optionName); ?>"
                            id="<?php echo $this->wordpressAdapter->escapeAttribute($id); ?>"
                            value="<?php echo $this->wordpressAdapter->escapeAttribute($value); ?>"
                            data-default-value="<?php echo $this->wordpressAdapter->escapeAttribute($defaultValue); ?>"
                        />
                        <?php
                        }
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </table>
            <p>
                To use this plugin, you must create an <strong>Open Weather Map</strong> account
                <a href="https://openweathermap.org/" target="_blank">here</a> and generate a key.
            </p>
            <p>
                Alternatively, you can use the default values provided with this plugin.
            </p>
            <p>
                The widget for this plugin can be added to your site by visiting the
                <a href="widgets.php">Widgets</a> page.
            </p>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
                <button id="btn-reset" class="button">Restore Defaults</button>
            </p>

<!--            --><?php //$this->wordpressAdapter->submitButton(); ?>
<!--            <button id="btn-reset" class="button">Restore Defaults</button>-->

        </form>
        <?php
    }
}
