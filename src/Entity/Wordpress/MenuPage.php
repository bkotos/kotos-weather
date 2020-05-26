<?php

namespace KotosWeather\Entity\Wordpress;

class MenuPage
{
    /** @var string */
    private $pageTitle;

    /** @var string */
    private $menuTitle;

    /** @var string */
    private $capability;

    /** @var string */
    private $menuSlug;

    /** @var callable */
    private $function;

    /** @var string */
    private $iconUrl;

    /** @var int */
    private $position;

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     * @return MenuPage
     */
    public function setPageTitle(string $pageTitle): MenuPage
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuTitle(): string
    {
        return $this->menuTitle;
    }

    /**
     * @param string $menuTitle
     * @return MenuPage
     */
    public function setMenuTitle(string $menuTitle): MenuPage
    {
        $this->menuTitle = $menuTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCapability(): string
    {
        return $this->capability;
    }

    /**
     * @param string $capability
     * @return MenuPage
     */
    public function setCapability(string $capability): MenuPage
    {
        $this->capability = $capability;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenuSlug(): string
    {
        return $this->menuSlug;
    }

    /**
     * @param string $menuSlug
     * @return MenuPage
     */
    public function setMenuSlug(string $menuSlug): MenuPage
    {
        $this->menuSlug = $menuSlug;
        return $this;
    }

    /**
     * @return string
     */
    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    /**
     * @param string $iconUrl
     * @return MenuPage
     */
    public function setIconUrl(string $iconUrl): MenuPage
    {
        $this->iconUrl = $iconUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return MenuPage
     */
    public function setPosition(int $position): MenuPage
    {
        $this->position = $position;
        return $this;
    }
}
