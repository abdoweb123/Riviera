<?php

use Illuminate\Support\Facades\Config;
use Modules\Country\Entities\Country;
use Modules\Setting\Entities\Model as Setting;

function vat()
{
    return 15;
}

function country_code()
{
    return 'BH';
}
function phone_code()
{
    return 973;
}

function lang($lang = null)
{
    if (isset($lang)) {
        return app()->islocale($lang);
    } else {
        return app()->getlocale();
    }
}

function format_number($number)
{
    return number_format($number, Country()->decimals, '.', ',');
}

function previous_route($lang = null)
{
    return str_replace('.create', '.index', str_replace('.edit', '.index', app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName()));
}

function location()
{
    if (request('lat') && request('long')) {
        return [request('lat'), request('long')];
    } else {
        if (! Config::get('Location')) {
            Config::set('Location', \Stevebauman\Location\Facades\Location::get(request()->ip()));
        }

        return [
            Config::get('Location')->latitude,
            Config::get('Location')->longitude,
        ];
    }
}

function activeGuard($CheckGuard = null)
{
    if ($CheckGuard) {
        $active = array_filter(explode('/', $_SERVER['REQUEST_URI']))[1];
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check() && $active == $guard) {
                return $guard == $CheckGuard;
            }
        }

        return str_replace('/', '', Request()->route()->getPrefix());
    } else {
        $active = array_filter(explode('/', $_SERVER['REQUEST_URI']))[1];
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check() && $active == $guard) {
                return $guard;
            }
        }

        return str_replace('/', '', Request()->route()->getPrefix());
    }
}

function Countries()
{
    if (! Config::get('Countries')) {
        Config::set('Countries', Country::Active()->get());
    }

    return Config::get('Countries');
}

function Country($id = 1)
{
    if (! Config::get('Country')) {
        Config::set('Country', Countries()->where('id', $id)->first());
    }

    return Config::get('Country');
}

function Settings()
{
    if (! Config::get('Settings')) {
        Config::set('Settings', Setting::Active()->get());
    }

    return Config::get('Settings');
}

function setting($key)
{
    return Settings()->where('key', $key)->first()?->value;
}


function getNearestCars($nearestCars)
{
    if (! Config::get('nearestCars')) {
        Config::set('nearestCars',$nearestCars);
    }

    return Config::get('nearestCars');
}


function DT_Lang()
{
    if (lang('ar')) {
        return '//cdn.datatables.net/plug-ins/1.10.16/i18n/Arabic.json';
    } else {
        return '//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json';
    }
}


function formatTimeDifference($start, $end)
{
    // Calculate the difference
    $diff = $start->diff($end);

    // Format the difference
    if ($diff->y > 0) {
        return [$diff->y, __('trans.years')];
    } elseif ($diff->m > 0) {
        return [$diff->m, __('trans.months')];
    } elseif ($diff->d > 0) {
        return [$diff->d, __('trans.days')];
    } elseif ($diff->h > 0) {
        return [$diff->h, __('trans.hours')];
    } elseif ($diff->i > 0) {
        return [$diff->i, __('trans.minutes')];
    } elseif ($diff->s > 0) {
        return [$diff->s, __('trans.seconds')];
    } else {
        return [0, __('trans.seconds')];
    }
}

