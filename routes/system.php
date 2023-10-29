<?php

$route = app('route');

// system
$route->get('__sitemap', 'SitemapController@index')->name('__sitemap');
$route->get('__locale/{locale}', 'LocaleController@set')->name('__locale');
$route->post('__file/url', 'FileController@url')->name('__file.url');
$route->get('__file/list', 'FileController@list')->name('__file.list');
$route->post('__file/upload', 'FileController@upload')->name('__file.upload');
$route->get('__file/{ulid}/{size?}', 'FileController@get')->name('__file.get');