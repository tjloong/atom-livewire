<?php

namespace Jiannius\Atom\Services;

class Lang
{
    // get all lang definition
    public static function all()
    {
        $atom = \Illuminate\Support\Facades\Lang::get('atom::app');
        $app = \Illuminate\Support\Facades\Lang::has('app')
            ? \Illuminate\Support\Facades\Lang::get('app')
            : [];

        $lang = [
            'app' => array_merge_recursive($atom, $app),
        ];

        return $lang;
    }

    // get js response
    public static function jsResponse()
    {
        \Barryvdh\Debugbar\Facades\Debugbar::disable();

        $lang = self::all();
        $content = \Illuminate\Support\Facades\Blade::render('window.lang = {{ Js::from($lang) }}', ['lang' => $lang]);

        return response($content)->header('Content-Type', 'application/javascript');
    }
}