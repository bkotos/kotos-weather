/**
 * @global kotosWeatherWidgetAdminUrl
 * @type {string}
 */

/**
 * @typedef WeatherForecast
 * @property {string} localeName
 * @property {number} timestamp
 * @property {string} formattedDate
 * @property {string} formattedTime
 * @property {string} forecastLabel
 * @property {string} forecastIconUrl
 * @property {number} actualTemperature
 * @property {number} feelsLikeTemperature
 * @property {number} minTemperature
 * @property {number} maxTemperature
 */

/**
 * @typedef TemperatureScale
 * @property {string} value
 * @property {string} label
 * @property {string} symbol
 */

/**
 * @param {jQuery} $element
 * @param {string} adminUrl
 * @constructor
 */
function WeatherWidget($element, adminUrl) {
    this.$element = $element;
    this.adminUrl = adminUrl;
}

/**
 * @static
 * @type {{WIDGET_CONTAINER: string}}
 */
WeatherWidget.SELECTORS = {
    WIDGET_CONTAINER: '.kotos-weather-widget-container',
    LBL_LOCALE_NAME: '.lbl-locale',
    TXT_LOCALE_SEARCH_QUERY: '.txt-locale-search-query',
    LBL_DATE: '.lbl-date',
    LBL_TIME: '.lbl-time',
    LBL_FORECAST: '.lbl-forecast',
    IMG_FORECAST: '.img-forecast',
    LBL_TEMPERATURE_SYMBOL: '.lbl-temperature-symbol',
    LBL_ACTUAL_TEMPERATURE: '.lbl-actual-temperature',
    LBL_FEELS_LIKE_TEMPERATURE: '.lbl-feels-like-temperature',
    LBL_MIN_TEMPERATURE: '.lbl-min-temperature',
    LBL_MAX_TEMPERATURE: '.lbl-max-temperature',
    PANE_SETTINGS: '.pane-settings',
    BTN_SHOW_SETTINGS: '.btn-show-settings',
    BTN_SAVE_SETTINGS: '.btn-save-settings',
    BTN_REFRESH: '.btn-refresh',
    SEL_TEMPERATURE_SCALE: '.sel-temperature-scale'
};

/**
 * @static
 * @type {Object.<string, TemperatureScale>}
 */
WeatherWidget.TEMPERATURE_SCALES = {
    celsius: {
        value: 'celsius',
        label: 'Celsius',
        symbol: 'C'
    },
    fahrenheit: {
        value: 'fahrenheit',
        label: 'Fahrenheit',
        symbol: 'F'
    },
    kelvin: {
        value: 'kelvin',
        label: 'Kelvin',
        symbol: 'K'
    }
};

/**
 * @static
 * @type {string}
 */
WeatherWidget.DEFAULT_TEMPERATURE_SCALE_VALUE = WeatherWidget.TEMPERATURE_SCALES.fahrenheit.value;

/**
 * @static
 * @type {string}
 */
WeatherWidget.PANE_ACTIVE_CLASS = 'pane-active';

/**
 * @static
 * @type {string}
 */
WeatherWidget.TEMPERATURE_SCALE_LOCAL_STORAGE_KEY = 'temperatureScale';

/**
 * @static
 * @type {string}
 */
WeatherWidget.LOCALE_SEARCH_QUERY_LOCAL_STORAGE_KEY = 'localeSearchQuery';

/**
 * Zip code for Central Park
 * @static
 * @type {string}
 */
WeatherWidget.DEFAULT_LOCALE_SEARCH_QUERY = '10019';

/**
 * @static
 * @type {WeatherForecast}
 */
WeatherWidget.PLACEHOLDER_WEATHER_FORECAST = {
    localeName: 'New York',
    timestamp: 0,
    formattedDate: 'Thu Jan 1',
    formattedTime: '12:00 am',
    forecastLabel: '-',
    forecastIconUrl: '',
    temperatureScale: WeatherWidget.TEMPERATURE_SCALES.fahrenheit.value,
    actualTemperature: 255.372,
    feelsLikeTemperature: 255.372,
    minTemperature: 255.372,
    maxTemperature: 255.372
};

/**
 * @static
 */
WeatherWidget.generateInstancesFromDom = function() {
    var $widgetElements = jQuery(WeatherWidget.SELECTORS.WIDGET_CONTAINER);

    for (var i = 0; i < $widgetElements.length; i++) {
        var instance = window.foo = new WeatherWidget($widgetElements.eq(i), kotosWeatherWidgetAdminUrl);
        instance.listen();
        instance.fetchWeatherForecast();
    }
};

WeatherWidget.prototype = {
    initialize: function() {
        this.listen();
        this.render();
        this.fetchWeatherForecast();
    },

    listen: function () {
        this.$element
            .on('click', WeatherWidget.SELECTORS.BTN_SHOW_SETTINGS, this.showSettingsPane.bind(this))
            .on('click', WeatherWidget.SELECTORS.BTN_SAVE_SETTINGS, this.onSettingsSave.bind(this))
            .on('click', WeatherWidget.SELECTORS.BTN_REFRESH, this.fetchWeatherForecast.bind(this))
        ;
    },

    onSettingsSave: function () {
        var localeSearchQuery = this.$element.find(WeatherWidget.SELECTORS.TXT_LOCALE_SEARCH_QUERY).val();
        var temperatureScaleValue = this.$element.find(WeatherWidget.SELECTORS.SEL_TEMPERATURE_SCALE).val();
        var isLocaleChanged = localeSearchQuery !== this.getLocaleSearchQuery();

        this.hideSettingsPane();
        this.setLocaleSearchQuery(localeSearchQuery);
        this.setTemperatureScale(temperatureScaleValue);
        this.render();

        // if the localeSearchQuery changed, fetch a new weather forecast
        if (isLocaleChanged) {
            this.fetchWeatherForecast()
        }
    },

    setLocaleSearchQuery: function (localeSearchQuery) {
        localStorage.setItem(WeatherWidget.LOCALE_SEARCH_QUERY_LOCAL_STORAGE_KEY, String(localeSearchQuery).trim());
    },

    /**
     * @returns {string}
     */
    getLocaleSearchQuery: function () {
        var localeSearchQuery = localStorage.getItem(WeatherWidget.LOCALE_SEARCH_QUERY_LOCAL_STORAGE_KEY);
        if (typeof localeSearchQuery !== 'string' || String(localeSearchQuery).length === 0) {
            localeSearchQuery = WeatherWidget.DEFAULT_LOCALE_SEARCH_QUERY;
        }

        return localeSearchQuery;
    },

    fetchWeatherForecast: function () {
        var localeSearchQuery = this.getLocaleSearchQuery();
        jQuery
            .ajax({
                type: 'GET',
                dataType: 'json',
                url: this.adminUrl,
                data: {
                    action: 'get_forecast',
                    locale: localeSearchQuery
                }
            })
            .done(this.onFetchWeatherForecastSuccess.bind(this))
            .fail(this.onFetchWeatherForecastFail.bind(this));
    },

    /**
     * @param {WeatherForecast} weatherForecast
     */
    onFetchWeatherForecastSuccess: function(weatherForecast) {
        // only use the weather forecast object if it's valid
        if (typeof weatherForecast === 'object' && weatherForecast.localeName) {
            this.setWeatherForecast(weatherForecast);
        }
        this.render();
    },

    onFetchWeatherForecastFail: function() {
        this.setWeatherForecast(WeatherWidget.PLACEHOLDER_WEATHER_FORECAST);
        this.render();
    },

    setWeatherForecast: function (weatherForecast) {
        this.weatherForecast = weatherForecast;
    },

    /**
     * @returns {WeatherForecast}
     */
    getWeatherForecast: function () {
        if (this.weatherForecast) {
            return this.weatherForecast;
        }

        return WeatherWidget.PLACEHOLDER_WEATHER_FORECAST;
    },

    render: function () {
        var weatherForecast = this.getWeatherForecast();
        var localeSearchQuery = this.getLocaleSearchQuery();

        var selectedTemperatureScale = this.getTemperatureScale();
        var selectedTemperatureSymbol = selectedTemperatureScale.symbol;
        var selectedTemperatureScaleValue = selectedTemperatureScale.value;

        var actualTemperature = this.convertTemperature(weatherForecast.actualTemperature, selectedTemperatureScaleValue);
        var feelsLikeTemperature = this.convertTemperature(weatherForecast.feelsLikeTemperature, selectedTemperatureScaleValue);
        var minTemperature = this.convertTemperature(weatherForecast.minTemperature, selectedTemperatureScaleValue);
        var maxTemperature = this.convertTemperature(weatherForecast.maxTemperature, selectedTemperatureScaleValue);

        console.log(this.$element.find(WeatherWidget.SELECTORS.IMG_FORECAST));
        console.log(weatherForecast.forecastIconUrl);
        console.log('----');

        this.$element.find(WeatherWidget.SELECTORS.LBL_LOCALE_NAME).text(weatherForecast.localeName);
        this.$element.find(WeatherWidget.SELECTORS.TXT_LOCALE_SEARCH_QUERY).val(localeSearchQuery);
        this.$element.find(WeatherWidget.SELECTORS.LBL_DATE).text(weatherForecast.formattedDate);
        this.$element.find(WeatherWidget.SELECTORS.LBL_TIME).text(weatherForecast.formattedTime);
        this.$element.find(WeatherWidget.SELECTORS.LBL_FORECAST).text(weatherForecast.forecastLabel);
        this.$element.find(WeatherWidget.SELECTORS.IMG_FORECAST).prop('src', weatherForecast.forecastIconUrl);
        this.$element.find(WeatherWidget.SELECTORS.LBL_TEMPERATURE_SYMBOL).text(selectedTemperatureSymbol);
        this.$element.find(WeatherWidget.SELECTORS.LBL_ACTUAL_TEMPERATURE).text(actualTemperature);
        this.$element.find(WeatherWidget.SELECTORS.LBL_FEELS_LIKE_TEMPERATURE).text(feelsLikeTemperature);
        this.$element.find(WeatherWidget.SELECTORS.LBL_MIN_TEMPERATURE).text(minTemperature);
        this.$element.find(WeatherWidget.SELECTORS.LBL_MAX_TEMPERATURE).text(maxTemperature);
        this.$element.find(WeatherWidget.SELECTORS.SEL_TEMPERATURE_SCALE).val(selectedTemperatureScaleValue);
    },

    showSettingsPane: function () {
        this.$element.find(WeatherWidget.SELECTORS.PANE_SETTINGS).addClass(WeatherWidget.PANE_ACTIVE_CLASS);
    },

    hideSettingsPane: function () {
        this.$element.find(WeatherWidget.SELECTORS.PANE_SETTINGS).removeClass(WeatherWidget.PANE_ACTIVE_CLASS);
    },

    /**
     * @param {string} temperatureScaleValue
     * @returns {boolean}
     */
    isValidTemperatureScaleValue: function (temperatureScaleValue) {
        return temperatureScaleValue && temperatureScaleValue in WeatherWidget.TEMPERATURE_SCALES;
    },

    /**
     * @returns {TemperatureScale}
     */
    getTemperatureScale: function () {
        var temperatureScale = localStorage.getItem(WeatherWidget.TEMPERATURE_SCALE_LOCAL_STORAGE_KEY);
        if (!this.isValidTemperatureScaleValue(temperatureScale)) {
            temperatureScale = WeatherWidget.DEFAULT_TEMPERATURE_SCALE_VALUE;
        }

        return WeatherWidget.TEMPERATURE_SCALES[temperatureScale];
    },

    setTemperatureScale: function (temperatureScaleValue) {
        if (this.isValidTemperatureScaleValue(temperatureScaleValue)) {
            localStorage.setItem(WeatherWidget.TEMPERATURE_SCALE_LOCAL_STORAGE_KEY, temperatureScaleValue);
        }
    },

    /**
     * @param {number} kelvinTemperature
     * @param {string} temperatureScale
     * @returns {number}
     */
    convertTemperature: function (kelvinTemperature, temperatureScale) {
        var temperature;
        switch (temperatureScale) {
            case WeatherWidget.TEMPERATURE_SCALES.celsius.value:
                temperature = (2 * kelvinTemperature) - 273.15;
                break;
            case WeatherWidget.TEMPERATURE_SCALES.kelvin.value:
                temperature = kelvinTemperature;
                break;
            case WeatherWidget.TEMPERATURE_SCALES.fahrenheit.value:
                temperature = (kelvinTemperature - 273.15) * (9 / 5) + 32;
                break;
        }

        return Number(Number(temperature).toFixed(1));
    }
};

jQuery(document).on('ready', function(){
    WeatherWidget.generateInstancesFromDom();
});

