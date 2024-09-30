<?php

namespace Jiannius\Atom\Services;

class Lang
{
    // get atom lang definition
    public static function atom()
    {
        return \Illuminate\Support\Facades\Lang::get('atom::app');
    }

    // get app lang definition
    public static function app()
    {
        return \Illuminate\Support\Facades\Lang::has('app')
            ? \Illuminate\Support\Facades\Lang::get('app')
            : [];
    }

    // get all lang definition
    public static function all()
    {
        $atom = self::atom();
        $app = self::app();

        return [
            'app' => array_merge_recursive($atom, $app),
        ];
    }

    // get js response
    public static function jsResponse()
    {
        \Barryvdh\Debugbar\Facades\Debugbar::disable();

        $lang = self::all();
        $content = \Illuminate\Support\Facades\Blade::render('window.lang = {{ Js::from($lang) }}', ['lang' => $lang]);

        return response($content)->header('Content-Type', 'application/javascript');
    }

    // translate
    public static function translate($str, $count = 1, $params = [])
    {
        if (empty($str)) return '';

        $atom = ['app' => self::atom()];
        $app = ['app' => self::app()];
        $key = null;

        if (str($str)->is('app.*')) {
            if (get($app, $str)) $key = $str;
            else if (get($atom, $str)) $key = 'atom::'.$str;
        }
        else if (get($app, 'app.label.'.$str)) $key = 'app.label.'.$str;
        else if (get($atom, 'app.label.'.$str)) $key = 'atom::app.label.'.$str;

        if (!$key) $key = $str;

        if (is_array($count)) return __($key, $count);
        if (!$count) return __($key, $params);

        return trans_choice($key, $count, $params);
    }
}