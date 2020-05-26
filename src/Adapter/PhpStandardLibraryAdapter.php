<?php

namespace KotosWeather\Adapter;

class PhpStandardLibraryAdapter implements PhpStandardLibraryAdapterInterface
{
    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getQueryParameter(string $key, $defaultValue = null)
    {
        if ($this->queryParameterExists($key)) {
            return $_GET[$key];
        }

        return $defaultValue;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function queryParameterExists(string $key): bool
    {
        return isset($_GET[$key]);
    }

    /**
     * @param string $content
     */
    public function echo($content)
    {
        echo $content;
    }

    /**
     * @param string $message
     * @return bool|void
     */
    public function errorLog(string $message)
    {
        error_log($message);
    }
}
