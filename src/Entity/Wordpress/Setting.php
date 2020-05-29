<?php

namespace KotosWeather\Entity\Wordpress;

class Setting
{
    /** @var string */
    private $optionGroup;

    /** @var string */
    private $optionName;

    /** @var string */
    private $label;

    /** @var string|null */
    private $defaultValue = null;

    /** @var string */
    private $value;

    /**
     * @return string
     */
    public function getOptionGroup(): string
    {
        return $this->optionGroup;
    }

    /**
     * @param string $optionGroup
     * @return self
     */
    public function setOptionGroup(string $optionGroup): self
    {
        $this->optionGroup = $optionGroup;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptionName(): string
    {
        return $this->optionName;
    }

    /**
     * @param string $optionName
     * @return self
     */
    public function setOptionName(string $optionName): self
    {
        $this->optionName = $optionName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     * @return self
     */
    public function setDefaultValue(string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
