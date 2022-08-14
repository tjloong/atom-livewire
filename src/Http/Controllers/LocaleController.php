<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    /**
     * Set locale
     */
    public function set($locale)
    {
        Cookie::queue('locale', $locale, 60 * 24 * 30);

        return back();
    }
}