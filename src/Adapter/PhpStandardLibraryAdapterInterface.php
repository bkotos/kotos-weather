<?php

namespace KotosWeather\Adapter;

interface PhpStandardLibraryAdapterInterface
{
    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getQueryParameter(string $key, $defaultValue = null);

    /**
     * @param string $key
     * @return bool
     */
    public function queryParameterExists(string $key): bool;

    /**
     * @param string $content
     */
    public function echo($content);

    /**
     * @param string $message
     */
    public function errorLog(string $message);
}
