<?php


namespace App\Facade;

/**
 *
 * @see SiteSettings
 */

use App\Services\SiteSettings;
use Illuminate\Support\Facades\Facade;

class SiteSetting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'SiteSetting';
    }

}
