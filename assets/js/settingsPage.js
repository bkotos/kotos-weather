/**
 * @constructor
 */
function SettingsPage() {}

SettingsPage.SELECTORS = {
    BTN_RESET: '#btn-reset',
    FRM_KOTOS_WEATHER: '#frm-kotos-weather',
    TXT_SETTING: '.setting-input'
};

SettingsPage.prototype = {
    /**
     * Setup DOM event listeners
     */
    listen: function () {
        jQuery(SettingsPage.SELECTORS.BTN_RESET).on('click', this.onBtnResetClick.bind(this));
    },

    /**
     * @param {Event} e
     */
    onBtnResetClick: function (e) {
        var $frmKotosWeather = jQuery(SettingsPage.SELECTORS.FRM_KOTOS_WEATHER);
        var $txtSetting = $frmKotosWeather.find(SettingsPage.SELECTORS.TXT_SETTING);

        // loop through form fields and reset the default values
        for (var i = 0; i < $txtSetting.length; i++) {
            var $currentTxtSetting = jQuery($txtSetting[i]);
            var defaultValue = $currentTxtSetting.data('default-value');
            $currentTxtSetting.val(defaultValue);
        }
    }
};

jQuery(document).on('ready', function(){
    var settingsPage = new SettingsPage();
    settingsPage.listen();
});
