<?php


namespace App\Services;


class SiteSettings
{
    public static $instance;
    private $settings;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getSiteSettings()
    {
        if (!$this->settings) {
            $this->settings = \App\Models\SiteSettings::first();
        }
        return $this->settings;
    }

    public function getText($key)
    {
        $siteSetting = $this->getSiteSettings();
        return $siteSetting->texts[$key] ?? '';
    }

    public function getField($key)
    {
        $siteSetting = $this->getSiteSettings();
        return $siteSetting->{$key} ?? '';
    }

}
