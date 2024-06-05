<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;

class LocaleController extends Controller
{
    public function __invoke()
    {
        $locale = request()->locale;

        if ($locale === 'js') return $this->getLangJs();

        // set app locale
        Cookie::queue('locale', $locale, 60 * 24 * 30);

        return back();
    }

    // get lang js
    public function getLangJs()
    {
        $lang = [
            'app' => array_merge_recursive(
                Lang::get('atom::app'),
                Lang::has('app') ? Lang::get('app') : [],
            ),
        ];

        Debugbar::disable();

        $content = Blade::render('window.lang = {{ Js::from($lang) }}', ['lang' => $lang]);
        
        return response($content)->header('Content-Type', 'application/javascript');
    }
}