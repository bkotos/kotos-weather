<?php

namespace KotosWeather\Widget;

use KotosWeather\Adapter\WordpressAdapterInterface;
use KotosWeather\Plugin;

class KotosWeatherWidget extends \WP_Widget
{
    /** @var WordpressAdapterInterface */
    private $wordpressAdapter;

    /**
     * Register widget with WordPress.
     */
    public function __construct(WordpressAdapterInterface $wordpressAdapter)
    {
        $this->wordpressAdapter = $wordpressAdapter;
        parent::__construct(
            Plugin::WIDGET_BASE_ID,
            esc_html__(Plugin::WIDGET_ADMIN_TITLE, Plugin::TEXT_DOMAIN), // Name
            ['description' => esc_html__(Plugin::DESCRIPTION, Plugin::TEXT_DOMAIN)] // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     * @see WP_Widget::widget()
     *
     */
    public function widget($args, $instance)
    {
        $this->wordpressAdapter->enqueueStyle('dashicons');
        $this->wordpressAdapter->enqueueStyle('weather-widget-css', 'assets/css/weatherWidget.css');
        $this->wordpressAdapter->enqueueScript('jquery');
        $this->wordpressAdapter->enqueueScript('weather-widget-js', 'assets/js/weatherWidget.js');

        $adminUrl = \admin_url('admin-ajax.php');
        $widgetInstanceId = rand(1, 100);

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo $args['after_widget'];
        ?>
        <script>
            var kotosWeatherWidgetAdminUrl = '<?php echo $this->wordpressAdapter->escapeJs($adminUrl); ?>';
        </script>
        <div class="kotos-weather-widget-container" data-widget-instance-id="<?php echo esc_attr($widgetInstanceId); ?>">
            <div class="pane pane-dashboard pane-active bg-sky text-center">
                <div class="button-container">
                    <button class="btn-widget btn-refresh">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </button>
                    <button class="btn-widget btn-show-settings">
                        <span class="dashicons dashicons-admin-generic"></span>
                    </button>
                </div>
                <div class="txt-small">
                    <span class="dashicons dashicons-location"></span>
                    <span class="lbl-locale">-</span>
                </div>
                <div class="txt-small">
                    <span class="lbl-date">-</span>
                    <span class="lbl-time">-</span>
                </div>
                <div class="border-bottom">
                    <span class="lbl-actual-temperature">-</span>
                    &deg;<span class="lbl-temperature-symbol">F</span>
                </div>
                <table>
                    <tr>
                        <td rowspan="5">
                            <img class="img-forecast" />
                        </td>
                    </tr>
                    <tr>
                        <th class="cell-heading">High</th>
                        <td class="cell-value">
                            <span class="lbl-max-temperature">-</span> &deg;<span class="lbl-temperature-symbol">F</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="cell-heading">Low</th>
                        <td class="cell-value">
                            <span class="lbl-min-temperature">-</span> &deg;<span class="lbl-temperature-symbol">F</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="cell-heading">Feels like</th>
                        <td class="cell-value">
                            <span class="lbl-feels-like-temperature">-</span> &deg;<span class="lbl-temperature-symbol">F</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="lbl-forecast">-</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="pane pane-overlay pane-settings bg-sky">
                <h2 class="pane-header text-center border-bottom">Settings</h2>
                <fieldset class="frm-field">
                    <label class="">
                        <span class="frm-label">Zip code:</span>
                        <input type="number" class="frm-input txt-locale-search-query" />
                    </label>
                </fieldset>
                <fieldset class="frm-field">
                    <label>
                        <span class="frm-label">Temperature scale:</span>
                        <select class="frm-input sel-temperature-scale">
                            <option value="celsius">Celsius</option>
                            <option value="fahrenheit">Fahrenheit</option>
                            <option value="kelvin">Kelvin</option>
                        </select>
                    </label>
                </fieldset>
                <fieldset class="frm-field">
                    <button class="frm-input btn-widget btn-save-settings">Save</button>
                </fieldset>
            </div>
        </div>
        <?php
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     * @see WP_Widget::form()
     *
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__(Plugin::WIDGET_DEFAULT_FRONTEND_TITLE, Plugin::TEXT_DOMAIN);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', Plugin::TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>

        <p></p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $newInstance Values just sent to be saved.
     * @param array $oldInstance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     * @see WP_Widget::update()
     *
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = [];
        $instance['title'] = (!empty($newInstance['title'])) ? sanitize_text_field($newInstance['title']) : '';

        return $instance;
    }

}
