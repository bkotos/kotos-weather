<?php

namespace KotosWeather\Entity\Wordpress;

class DropdownSetting extends Setting
{
    /** @var string[] */
    private $valueDictionary;

    /**
     * @return string[]
     */
    public function getValueDictionary(): array
    {
        return $this->valueDictionary;
    }

    /**
     * @param string[] $valueDictionary
     * @return self
     */
    public function setValueDictionary(array $valueDictionary): self
    {
        $this->valueDictionary = $valueDictionary;
        return $this;
    }
}
