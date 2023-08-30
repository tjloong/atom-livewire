<?php

$route = app('route');

// system
$route->get('__sitemap', 'SitemapController@index')->name('__sitemap');
$route->get('__locale/{locale}', 'LocaleController@set')->name('__locale');
$route->get('__file/{id}/{action?}', 'FileController@get')->name('__file');