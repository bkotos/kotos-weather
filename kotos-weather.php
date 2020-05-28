<?php
/**
 * @package KotosWeather
 */
/*
Plugin Name: Brian Kotos' Weather Plugin
Plugin URI: https://kotos.io/weather-wordpress-plugin
Description: A plugin for displaying the weather for any given location.
Version: 1.0.0
Author: Brian Kotos
Author URI: https://kotos.io
License: MIT
Text Domain: kotos_weather_widget_domain
 */
/*
Copyright 2020 Brian Kotos

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
if (!function_exists('add_action')) {
    echo 'Access denied.';
    http_response_code(504);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once '/usr/local/lib/vendor/autoload.php';

use KotosWeather\Plugin;
use Pimple\Container;
use KotosWeather\Service\ServiceProvider;

$container = new Container();
$container->register(new ServiceProvider());

/** @var Plugin $plugin */
$plugin = $container[Plugin::class];
$plugin->initialize();
