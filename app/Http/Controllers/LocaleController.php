<?php
// app/Http/Controllers/LocaleController.php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Change the application locale
     */
    public function setLocale($locale)
    {
        if (in_array($locale, config('app.supported_locales'))) {

            $cookie = Cookie::make('locale', $locale, 60 * 24 * 365); // minutes
            App::setLocale($locale);
            Config::set('app.locale', $locale);

            return redirect()->back()->withCookie($cookie);
        }

        return redirect()->back();
    }
}
